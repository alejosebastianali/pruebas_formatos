
Proyecto de Consulta de Válvulas - Documentación de Despliegue
===============================================================

Este proyecto permite realizar consultas técnicas sobre válvulas manuales y automáticas a través de un chatbot web, 
enviando las respuestas por correo electrónico y guardando un respaldo local en CSV.

ARCHIVOS INCLUIDOS
------------------
- index.html            -> Página principal con el chatbot.
- confirmacion.html     -> Página de agradecimiento tras envío exitoso.
- enviar.php            -> Script que procesa las respuestas, envía el correo y guarda en CSV.
- .env                  -> Configuración SMTP (correo emisor y receptor).
- consultas.csv         -> Respaldo local de las consultas realizadas.

REQUISITOS
----------
- Servidor web con soporte PHP (ej: Apache, Nginx + PHP).
- Composer (para instalar dependencias).
- Acceso a un servidor SMTP (ej: Gmail, Outlook, SendGrid, etc.).

INSTRUCCIONES DE INSTALACIÓN
----------------------------
1. Suba todos los archivos a su servidor web.
2. Edite el archivo `.env` con sus credenciales SMTP reales:

   SMTP_HOST=smtp.su-servidor.com
   SMTP_PORT=465
   SMTP_USERNAME=su-correo@dominio.com
   SMTP_PASSWORD=su-contraseña
   SMTP_SECURE=ssl

   FROM_EMAIL=su-correo@dominio.com
   TO_EMAIL=destinatario@dominio.com

3. Desde el directorio del proyecto, instale PHPMailer:

   composer require phpmailer/phpmailer vlucas/phpdotenv

4. Asegúrese de que PHP tenga permisos de escritura sobre `consultas.csv` (si desea respaldos locales).

5. Acceda a `index.html` desde el navegador para iniciar el chatbot.

SOPORTE
-------
Para dudas técnicas o personalizaciones, contacte al desarrollador del sistema.

