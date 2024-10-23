
let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function () {
    iniciaApp();
});

function iniciaApp() {
    mostrarSeccion();//Muestra la seccion actual 
    tabs(); //Cambia la seccion cuando se presiones los tabs
    botonesPaginacion();
    paginaAnterior();
    paginaSiguiente();
    consultarServicios();//Consulta los servicios mediante una api
    idCliente();//Añade el id del cliente al objeto de cita
    nombreCliente();//Añade el nombre del cliente en el objeto de cita
    seleccionarFecha();//Añade la fecha en el objeto de cita
    seleccionarHora();//Añade la hora en el objeto de cita

    mostrarResumen();
}

function mostrarSeccion() {
    //Ocultamos la seccion atenrior
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    //Mostramos la seccion que se selecciono
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //Quita la clase actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`) //Con [] se selecciona un atributo que hayamos creado 
    tab.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton => {
        boton.addEventListener('click', function (e) {
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginacion();
        })
    });
}

function botonesPaginacion() {
    const botonAnterior = document.querySelector('#anterior');
    const botonSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        botonAnterior.classList.add('ocultar');
        botonSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        botonAnterior.classList.remove('ocultar');
        botonSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        botonAnterior.classList.remove('ocultar');
        botonSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}


function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');

    paginaAnterior.addEventListener('click', function () {
        if (paso <= pasoInicial) return;
        paso--;
        botonesPaginacion();
    });

}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');

    paginaSiguiente.addEventListener('click', function () {
        if (paso >= pasoFinal) return;
        paso++;
        botonesPaginacion();
    });

}
async function consultarServicios() {
    try {
        const url = '/api/servicios';// Definimos la URL de la API
        const resultado = await fetch(url); // Se espera la respuesta de la petición fetch
        const servicios = await resultado.json(); // Convierte la respuesta a JSON
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {

    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio; //Destruction al objeto

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;


        const servicioDiv = document.createElement('DIV')
        servicioDiv.classList.add('servicio')
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };

        servicioDiv.append(nombreServicio, precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    })
}

function seleccionarServicio(servicio) {
    const { id } = servicio; //Servicio que seleccionamos
    const { servicios } = cita;//Extraemos el arreglo servicios de cita
    const divServicio = document.querySelector(`[data-id-servicio = "${id}"]`);

    if (servicios.some(agregado => agregado.id === id)) {
        //Eliminamos el servicio por que lo deselecciono 
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado')
    } else {

        cita.servicios = [...servicios, servicio]; //creamos una copia del arrlgo y agregamos el servicio nuevo 
        divServicio.classList.add('seleccionado');
    }

}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;
    cita.nombre = nombre;
}
function idCliente() {
    const idCliente = document.querySelector('#id').value;
    cita.id = idCliente;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha')
    inputFecha.addEventListener('change', function (e) {
        const dia = new Date(e.target.value).getUTCDay();

        if ([0, 6].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Lo sentimos, los fines de semana no abrimos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }

    })
}
function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('change', function (e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if (hora < 10 || hora >= 18) {
            e.target.value = '';
            mostrarAlerta('Selecciona una hora entre las 10 AM y 6 PM', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //Previene que se generen oas de una alerta 
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta)

    if (desaparece) {
        //eliminamos la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiera e HTML previo 
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if (cita.servicios.length === 0) {
        mostrarAlerta('Por favor seleciona un servicio', 'error', '.contenido-resumen', false);
        return;
    } else if (Object.values(cita).includes('')) {//Convierte el arreglo en un array y valia si tiene un valor vacio
        mostrarAlerta('Por favor seleciona la Fecha y Hora', 'error', '.contenido-resumen', false);
        return;
    }

    //Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    //Formater la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2; //Importante cuando se instancia el objeto date quita un dia, lo vamos a usar 2 veces
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones)

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span>${hora} Horas`;

    //Titulo de los servicios
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios'
    //Titulo de los servicios
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita'


    resumen.append(headingCita, nombreCliente, fechaCita, horaCita, headingServicios);



    //Servicios es un arrglo pos lo que debemos de iterar sobre este array
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textServicio = document.createElement('P');
        textServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`;

        contenedorServicio.append(textServicio, precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(botonReservar);

}

async function reservarCita() {

    const { nombre, fecha, hora, servicios, id } = cita;
    const idServicios = servicios.map(servicio => servicio.id);

    const datos = new FormData();
    datos.append('usuarioid', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicios);

    try {
        const url = '/api/citas'

        const respuesta = await fetch(url, {
            method: 'POST', //Son datos adicionalea aue se le agregan al servidor en este caso le decimos que el metodo sera de tipo POST
            body: datos
        })

        const resultado = await respuesta.json();

        if (resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                button: "OK"
            }).then(() => {
                window.location.reload();
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar tu cita.",
          });
    }



}