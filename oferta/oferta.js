// ============================================================
//  CRUD — Oferta de Trabajo
//  Archivo: oferta.js
// ============================================================

const API = 'oferta_api.php';

// ---------- Estado ----------
let editandoId = null;

// ---------- DOM listo ----------
document.addEventListener('DOMContentLoaded', () => {
    cargarOfertas();
    document.getElementById('form-oferta').addEventListener('submit', manejarEnvio);
    document.getElementById('btn-cancelar').addEventListener('click', cancelarEdicion);
    document.getElementById('btn-nueva').addEventListener('click', () => mostrarFormulario(false));
    document.getElementById('modal-confirmar-si').addEventListener('click', confirmarEliminar);
    document.getElementById('modal-confirmar-no').addEventListener('click', cerrarModal);
});

// ============================================================
//  LISTAR
// ============================================================
async function cargarOfertas() {
    try {
        mostrarCargaTabla(true);
        const res = await fetch(`${API}?accion=listar`);
        const data = await res.json();

        if (!data.ok) throw new Error(data.mensaje);

        renderizarTabla(data.ofertas);
    } catch (e) {
        mostrarToast('Error al cargar ofertas: ' + e.message, 'error');
    } finally {
        mostrarCargaTabla(false);
    }
}

function renderizarTabla(ofertas) {
    const tbody = document.getElementById('tabla-body');
    tbody.innerHTML = '';

    if (!ofertas || ofertas.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-12 text-slate-400 text-sm uppercase tracking-widest">
                    No hay ofertas registradas
                </td>
            </tr>`;
        return;
    }

    ofertas.forEach(o => {
        const tr = document.createElement('tr');
        tr.className = 'table-row-hover border-b border-slate-100 transition-colors';
        tr.innerHTML = `
            <td class="py-4 px-6 text-sm font-semibold text-on-surface">${escaparHTML(o.cargo)}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${escaparHTML(o.perfil)}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${escaparHTML(o.cod_ciudad || '—')}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${escaparHTML(o.salario || '—')}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${escaparHTML(o.num_vacante)}</td>
            <td class="py-4 px-6">
                <div class="flex gap-2">
                    <button onclick="cargarParaEditar(${o.cod_oferta})"
                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-primary border border-primary rounded hover:bg-secondary-container transition-colors">
                        <span class="material-symbols-outlined text-base">edit</span> Editar
                    </button>
                    <button onclick="pedirConfirmacion(${o.cod_oferta})"
                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-error border border-error rounded hover:bg-error-container transition-colors">
                        <span class="material-symbols-outlined text-base">delete</span> Eliminar
                    </button>
                </div>
            </td>`;
        tbody.appendChild(tr);
    });
}

// ============================================================
//  CREAR / ACTUALIZAR
// ============================================================
async function manejarEnvio(e) {
    e.preventDefault();

    if (!validarFormulario()) return;

    const datos = obtenerDatosFormulario();
    const btnSubmit = document.getElementById('btn-submit');
    activarSpinner(btnSubmit, true);

    try {
        const body = new URLSearchParams({
            accion: editandoId ? 'actualizar' : 'crear',
            ...datos,
            ...(editandoId ? { cod_oferta: editandoId } : {})
        });

        const res = await fetch(API, { method: 'POST', body });
        const data = await res.json();

        if (!data.ok) throw new Error(data.mensaje);

        mostrarToast(
            editandoId ? 'Oferta actualizada correctamente.' : 'Oferta publicada correctamente.',
            'exito'
        );
        cancelarEdicion();
        cargarOfertas();
    } catch (e) {
        mostrarToast('Error: ' + e.message, 'error');
    } finally {
        activarSpinner(btnSubmit, false);
    }
}

// ============================================================
//  CARGAR PARA EDITAR
// ============================================================
async function cargarParaEditar(id) {
    try {
        const res = await fetch(`${API}?accion=obtener&cod_oferta=${id}`);
        const data = await res.json();

        if (!data.ok) throw new Error(data.mensaje);

        const o = data.oferta;
        editandoId = id;

        document.getElementById('cargo').value         = o.cargo || '';
        document.getElementById('perfil').value        = o.perfil || '';
        document.getElementById('salario').value       = o.salario || '';
        document.getElementById('requerimientos').value= o.requerimientos || '';
        document.getElementById('experiencia').value   = o.experiencia || '';
        document.getElementById('num_vacante').value   = o.num_vacante || '';
        document.getElementById('horario').value       = o.horario || '';
        document.getElementById('duracion').value      = o.duracion || '';
        document.getElementById('nom_software_maneja').value = o.nom_software_maneja || '';
        document.getElementById('nivel_manejo_software').value = o.nivel_manejo_software || '';
        document.getElementById('nit_empresa').value   = o.nit_empresa || '';
        document.getElementById('cod_discapacidad').value = o.cod_discapacidad || '';
        document.getElementById('cod_contrato').value  = o.cod_contrato || '';
        document.getElementById('cod_nivel_edu').value = o.cod_nivel_edu || '';
        document.getElementById('cod_idioma').value    = o.cod_idioma || '';
        document.getElementById('cod_ciudad').value    = o.cod_ciudad || '';

        mostrarFormulario(true);
        document.getElementById('form-container').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        mostrarToast('Error al cargar oferta: ' + e.message, 'error');
    }
}

// ============================================================
//  ELIMINAR
// ============================================================
let idEliminar = null;

function pedirConfirmacion(id) {
    idEliminar = id;
    document.getElementById('modal-backdrop').classList.remove('hidden');
}

function cerrarModal() {
    idEliminar = null;
    document.getElementById('modal-backdrop').classList.add('hidden');
}

async function confirmarEliminar() {
    cerrarModal();
    if (!idEliminar) return;

    try {
        const body = new URLSearchParams({ accion: 'eliminar', cod_oferta: idEliminar });
        const res = await fetch(API, { method: 'POST', body });
        const data = await res.json();

        if (!data.ok) throw new Error(data.mensaje);
        mostrarToast('Oferta eliminada.', 'exito');
        cargarOfertas();
    } catch (e) {
        mostrarToast('Error al eliminar: ' + e.message, 'error');
    }
}

// ============================================================
//  HELPERS
// ============================================================
function obtenerDatosFormulario() {
    return {
        cargo:                document.getElementById('cargo').value.trim(),
        perfil:               document.getElementById('perfil').value.trim(),
        salario:              document.getElementById('salario').value.trim(),
        requerimientos:       document.getElementById('requerimientos').value.trim(),
        experiencia:          document.getElementById('experiencia').value.trim(),
        num_vacante:          document.getElementById('num_vacante').value.trim(),
        horario:              document.getElementById('horario').value.trim(),
        duracion:             document.getElementById('duracion').value.trim(),
        nom_software_maneja:  document.getElementById('nom_software_maneja').value.trim(),
        nivel_manejo_software:document.getElementById('nivel_manejo_software').value.trim(),
        nit_empresa:          document.getElementById('nit_empresa').value.trim(),
        cod_discapacidad:     document.getElementById('cod_discapacidad').value.trim(),
        cod_contrato:         document.getElementById('cod_contrato').value.trim(),
        cod_nivel_edu:        document.getElementById('cod_nivel_edu').value.trim(),
        cod_idioma:           document.getElementById('cod_idioma').value.trim(),
        cod_ciudad:           document.getElementById('cod_ciudad').value.trim(),
    };
}

function validarFormulario() {
    const cargo = document.getElementById('cargo').value.trim();
    const nit   = document.getElementById('nit_empresa').value.trim();

    if (!cargo) {
        mostrarToast('El campo Cargo es obligatorio.', 'error');
        document.getElementById('cargo').focus();
        return false;
    }
    if (!nit) {
        mostrarToast('El campo NIT de Empresa es obligatorio.', 'error');
        document.getElementById('nit_empresa').focus();
        return false;
    }
    return true;
}

function cancelarEdicion() {
    const nitEmpresa = document.getElementById('nit_empresa').value;
    editandoId = null;
    document.getElementById('form-oferta').reset();
    document.getElementById('nit_empresa').value = nitEmpresa;
    mostrarFormulario(false);
}

function mostrarFormulario(esEdicion) {
    const container = document.getElementById('form-container');
    const titulo    = document.getElementById('form-titulo');
    const subtitulo = document.getElementById('form-subtitulo');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancel = document.getElementById('btn-cancelar');

    container.classList.remove('hidden');

    if (esEdicion) {
        titulo.textContent    = 'Editar Oferta de Trabajo';
        subtitulo.textContent = 'Modifica los datos de la oferta seleccionada.';
        btnSubmit.querySelector('span:last-child').textContent = 'Actualizar Oferta';
        btnCancel.classList.remove('hidden');
    } else {
        titulo.textContent    = 'Registrar Oferta de Trabajo';
        subtitulo.textContent = 'Complete los datos para publicar una nueva vacante en el sistema del observatorio.';
        btnSubmit.querySelector('span:last-child').textContent = 'Publicar Oferta';
        btnCancel.classList.add('hidden');
    }
}

function mostrarCargaTabla(loading) {
    const spinner = document.getElementById('tabla-spinner');
    if (spinner) spinner.classList.toggle('hidden', !loading);
}

function activarSpinner(btn, activo) {
    const spinnerEl = btn.querySelector('.spinner');
    const textoEl   = btn.querySelector('span:last-child');
    if (activo) {
        spinnerEl.classList.remove('hidden');
        btn.disabled = true;
    } else {
        spinnerEl.classList.add('hidden');
        btn.disabled = false;
    }
}

function mostrarToast(mensaje, tipo = 'exito') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const msg   = document.getElementById('toast-msg');

    msg.textContent = mensaje;

    if (tipo === 'error') {
        toast.className = toast.className.replace(/bg-\S+/, '');
        toast.classList.add('bg-red-600');
        icon.textContent = 'error';
    } else {
        toast.className = toast.className.replace(/bg-\S+/, '');
        toast.classList.add('bg-primary');
        icon.textContent = 'check_circle';
    }

    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3500);
}

function escaparHTML(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}