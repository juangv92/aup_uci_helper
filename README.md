# AUP-UCI, Sistema de apoyo al desarrollo de software en la UCI

/*------------------------------------------------------------------*/

Tecnologías base del proyecto:
* Yii 2.0.35
* XAMPP 7.4.7(PHP, MySQL)
* La plantilla del backend es AdminLTE.

/*------------------------------------------------------------------*/

La primera vez y con el objetivo de la carga inicial de la BD se debe ejecutar el comando:
```
yii migrate
```

**NOTA:** Con la ejecución del comando se crean las tablas básicas y se puede acceder al backend con usuario y contraseña "superadmin"

/*------------------------------------------------------------------*/

Ejemplo de mensajes flash:
```
GlobalFunctions::addFlashMessage('success',Yii::t('backend','Usuario eliminado satisfactoriamente'));
GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el usuario'));
GlobalFunctions::addFlashMessage('info',Yii::t('backend','Texto informativo'));
GlobalFunctions::addFlashMessage('warning',Yii::t('backend','Texto de alerta'));
```

/*------------------------------------------------------------------*/

Para mejor organización y entendimiento de los commit del git, se propone describirlos con mensajes de la forma:
* **KEY**: descripción

Donde 'KEY' será:
* **ADD** para creación de nuevos archivos
* **CHG** para modificaciones de archivos
* **FIX** para corrección de errores
* **DEL** para eliminaciones 

Ejemplos:
```
ADD: agregando ficheros asociados al CRUD de usuarios
CHG: cambio para mejorar visualización del formulario
FIX: corrección de error de validación
DEL: eliminado archivos sobrantes del CRUD
```

/*------------------------------------------------------------------*/

Configuración el virtual host del XAMPP:
* Modificar el fichero XAMPP/apache/conf/extra/httpd-vhost.conf:
```
<VirtualHost *:80>
    ServerAdmin ecperez@estudiantes.uci.cu
    DocumentRoot "path/to/aup_uci_helper/backend/web"
	ServerName www.aup-helper.uci.cu.local
	ServerAlias aup-helper.uci.cu.local
    ErrorLog "logs/aup_uci_helper-error.log"
    CustomLog "logs/aup_uci_helper-access.log" common
</VirtualHost>
```

* Modificar el archivo "C:\Windows\System32\drivers\etc\hosts" y agregar:
```
...
# localhost name resolution is handled within DNS itself.
#	127.0.0.1       localhost
   	127.0.0.1       www.aup-helper.uci.cu.local
```

