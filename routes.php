<?php
// routes.php
Router::get('/', 'LoginControl@index'); // RUTA PARA LOGIN CUANDO NO ESTÁS LOGEADO ES LO PRIMERO
Router::post('/login', 'LoginControl@ingresar'); //RUTA PARA CUANDO SE PRESIONE LOGIN, ESTA ES LA FUNCIÓN DE LOGEARSE
Router::get('/registro', 'LoginControl@registroIndex'); //RUTA PARA REGISTRO VISTA
Router::post('/registro', 'LoginControl@registro'); //RUTA PARA LA FUNCIÓN DE REGISTRARSE
Router::get('/main', 'LoginControl@main'); //RUTA PARA LA VISTA MAIN
Router::get('/logout','LoginControl@logout'); //RUTA PARA DESLOGEARSE, SALIR
Router::get('/validar-sesion', 'LoginControl@validarSesionAjax'); //RUTA PARA VALIDAR SESIÓN (EN CONJUNTO CON JAVASCRIPT)
Router::get('/noti', 'LoginControl@solicitud_notificacion'); 
Router::get('/marcar_vistas', 'LoginControl@marcar_vistas');
Router::get('/marcar_vistasDespacho', 'LoginControl@marcar_vistasDespacho');
Router::get('/solicitudes_list','SolicitudControl@lista'); //RUTA PARA LA VISTA DE SOLICITUDES_LIST Y A LA MISMA VEZ MOSTRAR LA TABLA SOLICITUD_AYUDA
Router::get('/busqueda','SolicitudControl@busquedaVista'); //RUTA PARA BUSQUEDA VISTA, PARA LA CEDULA ANTES DE RELLENAR FORMULARIO
Router::post('/formulario','SolicitudControl@buscar'); // RUTA PARA FUNCIÓN DE BUSCAR CEDULA EN TABLA SOLICITANTE
Router::get('/formulario','SolicitudControl@formulario'); //RUTA PARA VISTA DEL FORMULARIO, LO QUE SE TIENE QUE ENVIAR PARA LA SOLICITUD
Router::post('/enviarFormulario','SolicitudControl@enviarFormulario');//RUTA PARA FUNCIÓN DE ENVIAR FORMULARIO A TODAS LAS TABLAS INFORMACIÓN
Router::get('/felicidades','SolicitudControl@felicidades'); //RUTA PARA QUE EN CASO DE HABER SUBIDO CON ÉXITO EL FORMULARIO, FELICIDADES AQUÍ
Router::get('/procesar','SolicitudControl@procesar');//RUTA PARA PROCESAR LAS SOLICITUDES(ENVIAR A DESPACHO,ADMINISTRACIÓN)
Router::get('/beneficiario_info','SolicitudControl@beneficiario_info');
Router::get('/inhabilitar','SolicitudControl@inhabilitar');
Router::post('/inhabilitar_solicitud','SolicitudControl@inhabilitar_solicitud');
Router::get('/inhabilitados_lista','SolicitudControl@inhabilitados_lista');
Router::get('/habilitar','SolicitudControl@habilitar');
Router::get('/editar','SolicitudControl@editar');
Router::post('/editar_solicitud','SolicitudControl@editar_solicitud');
Router::get('/filtrar','SolicitudControl@filtrar');
Router::post('/filtrar_fecha','SolicitudControl@filtrar_fecha');
Router::get('/despacho_busqueda','DespachoControl@despacho_busqueda');
Router::post('/buscar_cidespacho','DespachoControl@buscar');
Router::post('/despacho_enviarForm','DespachoControl@enviarFormulario');
Router::get('/despacho_list','DespachoControl@despacho_list');
Router::get('/procesarDespacho','DespachoControl@procesar');
Router::get('/inhabilitados_despacho','DespachoControl@inhabilitados_lista');
Router::get('/inhabilitarDespacho','DespachoControl@inhabilitar');
Router::post('/inhabilitar_solicitudDespacho','DespachoControl@inhabilitar_solicitud');
Router::get('/habilitarDespacho','DespachoControl@habilitar');
Router::get('/beneficiario_infoDespacho','DespachoControl@beneficiario_info');
Router::get('/editarDespacho','DespachoControl@editar');
Router::post('/editar_solicitudDespacho','DespachoControl@editar_solicitud');
Router::get('/reportes','ReportesControl@reportes_entradas');
Router::post('/fecha_reportes','ReportesControl@filtrar_fecha');
Router::get('/reportes_acciones','ReportesControl@reportes_acciones');
Router::post('/filtro_acciones','ReportesControl@filtrar_acciones');
Router::get('/limites','ReportesControl@limites');
Router::get('/limite_editar','ReportesControl@edit_limite');
Router::post('/consulta_limite','ReportesControl@consulta_limite');
Router::get('/informacion_beneficiario','BeneficiarioControl@mostrar');
Router::get('/beneficiarios_lista','BeneficiarioControl@beneficiarios_list');
Router::get('/registro_beneficiario','BeneficiarioControl@registro_beneficiario');
Router::post('/registrar_beneficiario','BeneficiarioControl@registrar_beneficiario');
Router::get('/felicidades_beneficiario','BeneficiarioControl@felicidades_beneficiario');
Router::post('/buscar_beneficiario','BeneficiarioControl@buscar_beneficiario');
Router::get('/solicitudes_beneficiario','BeneficiarioControl@solicitudes_beneficiario');
Router::get('/estadisticas','EstadisticasControl@estadisticas');
Router::get('/constancias','ConstanciasControl@mostrar');
Router::get('/registro_constancia','ConstanciasControl@registro_constancia');
Router::post('/registrar_constancia','ConstanciasControl@registrar_constancia');
Router::get('/felicidades_constancia','ConstanciasControl@felicidades_constancia');
Router::get('/recuperacion_clave','LoginControl@recuperacion_clave');
Router::post('/recuperar_clave','LoginControl@recuperar_clave');
Router::post('/nueva_clave','LoginControl@nueva_clave');
Router::post('/actualizar_clave','LoginControl@actualizar_clave');
Router::post('/solicitudes_ci','SolicitudControl@solicitudes_ci');
Router::get('/solicitudes_desarrollo','DesarrolloControl@lista');
Router::get('/buscar_desarrollo','DesarrolloControl@buscar_desarrollo');
Router::post('/formulario_desarrollo','DesarrolloControl@formulario_desarrollo');
Router::post('/enviar_formulario_desarrollo','DesarrolloControl@enviar_formulario_desarrollo');
Router::get('/felicidades_desarrollo','DesarrolloControl@felicidades_desarrollo');
Router::get('/procesarDesarrollo','DesarrolloControl@procesar');
Router::post('/solicitudes_ciDesarrollo','DesarrolloControl@registrar');
Router::get('/inhabilitarDesarrollo','DesarrolloControl@inhabilitar_vista');
Router::post('/inhabilitar_desarrollo','DesarrolloControl@inhabilitar');
Router::get('/desarrollo_invalidos','DesarrolloControl@inhabilitados_lista');
Router::get('/habilitarDesarrollo','DesarrolloControl@habilitar');
Router::get('/editarDesarrollo','DesarrolloControl@editar');
Router::post('/editar_desarrollo','DesarrolloControl@editar_solicitud');
Router::get('/filtrar_desarrollo','DesarrolloControl@filtrar_desarrollo');
Router::get('/mostrar_noti_urgencia','DesarrolloControl@mostrar_noti_urgencia');
Router::get('/casos_lista', 'AtencionControl@casos_lista');
Router::get('/casos_ci_busqueda', 'AtencionControl@casos_ci_busqueda');
Router::post('/casos_formulario', 'AtencionControl@casos_formulario');
Router::post('/casos_enviar', 'AtencionControl@casos_enviar');
Router::get('/felicidades_casos', 'AtencionControl@felicidades_casos');
Router::post('/casos_anteriores', 'AtencionControl@casos_anteriores');
Router::get('/atender_caso', 'AtencionControl@atender_caso');
Router::get('/continuar_caso', 'AtencionControl@continuar_caso');
Router::get('/marcar_vistas_new', 'AtencionControl@marcar_vistas_new');
Router::get('/noti_caso', 'AtencionControl@atender_caso');
Router::get('/felicidades_despacho', 'DespachoControl@felicidades_despacho');
Router::post('/caso_continuar','AtencionControl@caso_continuar');
Router::get('/felicidades_caso_continuado','AtencionControl@felicidades_caso_continuado');
Router::get('/casos_procesos_lista','AtencionControl@casos_procesos_lista');
Router::get('/accion','AtencionControl@accion');
?>