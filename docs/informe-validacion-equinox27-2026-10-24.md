# Informe de validación ampliada — CVE-2026-109618 y CVE-2026-109619 — equinox27.com

**Fecha de este informe:** 2026-10-24
**Sistema:** Servidor de producción de equinox27.com
**Responsable:** soporte@heka.com.ec
**Estado:** Evidencia ampliada para cierre de validación — reemplaza al informe corto `subsanacion-CVE-2026-109618-109619.md` (2026-10-23), que quedó incompleto frente a lo pedido por el revisor.

**Fuera de alcance de este documento (lo confirma directamente quien hace la revisión, no se recolectó aquí):** hora exacta y zona horaria del parche, y las verificaciones hechas por Sebastián como revisor. Todo lo demás pedido para Equinox está cubierto abajo, con el comando ejecutado y su salida.

---

## 1. Resumen de las vulnerabilidades

| CVE | Tipo | CVSS v4.0 | Descripción |
|---|---|---|---|
| CVE-2026-109618 | Corrupción de memoria | 9.2 | PHP se queda sin memoria a mitad de construir una request; el estado a medio construir queda marcado como válido y la limpieza posterior confía en memoria no inicializada. |
| CVE-2026-109619 | Use-after-free | 9.2 | Estado residual de una request apunta a memoria ya liberada; una request posterior en el mismo worker puede reutilizar esa memoria. |

Afecta al **núcleo de PHP** ("PHP2Shell"), no al código de la app Laravel ni a sus dependencias Composer. RCE sin autenticación. Hubo explotación activa reportada desde el 17 de octubre, antes de existir parche oficial.

Versiones afectadas: PHP 8.2.x < 8.2.35, 8.3.x < 8.3.36, 8.4.x < 8.4.27, 8.5.x < 8.5.12.
Versiones corregidas: 8.2.35, 8.3.36, 8.4.27, 8.5.12.

---

## 2. Evidencia técnica de la corrección

### 2.1 Versión de PHP-FPM (salida cruda)
```
$ /usr/sbin/php-fpm8.2 -v
PHP 8.2.35 (fpm-fcgi) (built: Oct 21 2026 06:29:51)
Copyright (c) The PHP Group
Zend Engine v4.2.35, Copyright (c) Zend Technologies
    with Zend OPcache v8.2.35, Copyright (c), by Zend Technologies
```

### 2.2 apt-cache policy de los paquetes PHP instalados
Todos los paquetes `php8.2-*` están en `8.2.35-1~deb12u1` (instalado = candidato, nada pendiente), origen `bookworm-security`:
```
$ dpkg -l | awk '/^ii/ && /php/ {print $2}' | xargs apt-cache policy
php-common:
  Installed: 2:93
  Candidate: 2:93
php8.2-bcmath:
  Installed: 8.2.35-1~deb12u1
  Candidate: 8.2.35-1~deb12u1
  Version table:
 *** 8.2.35-1~deb12u1 500
        500 mirror+file:/etc/apt/mirrors/debian-security.list bookworm-security/main amd64 Packages
     8.2.32-1~deb12u1 500
        500 mirror+file:/etc/apt/mirrors/debian.list bookworm/main amd64 Packages
[... idéntico patrón (Installed = Candidate = 8.2.35-1~deb12u1, origen bookworm-security) para:
php8.2-cli, php8.2-common, php8.2-curl, php8.2-fpm, php8.2-gd, php8.2-intl,
php8.2-mbstring, php8.2-opcache, php8.2-readline, php8.2-sqlite3, php8.2-xml, php8.2-zip]
```

### 2.3 /var/log/apt/history.log — extracto relevante
El parche **se aplicó manualmente**, no por unattended-upgrades:
```
Start-Date: 2026-10-23  00:34:16
Commandline: apt-get install -y --only-upgrade php-common php8.2-bcmath php8.2-cli php8.2-common php8.2-curl php8.2-fpm php8.2-gd php8.2-intl php8.2-mbstring php8.2-opcache php8.2-readline php8.2-sqlite3 php8.2-xml php8.2-zip
Requested-By: admin (1000)
Upgrade: php8.2-mbstring:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-curl:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-bcmath:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-opcache:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-intl:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-readline:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-gd:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-sqlite3:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-cli:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-fpm:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-common:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-xml:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1), php8.2-zip:amd64 (8.2.34-1~deb12u1, 8.2.35-1~deb12u1)
End-Date: 2026-10-23  00:34:34
```
Entradas de unattended-upgrades alrededor de esa fecha (sin relación con el parche PHP):
```
Start-Date: 2026-10-21  06:13:17
Upgrade: ca-certificates:amd64 (20250419~deb12u1, 20250419~deb12u2)
End-Date: 2026-10-21  06:13:22

Start-Date: 2026-10-23  06:04:35
Upgrade: libexpat1:amd64 (2.5.0-1+deb12u4, 2.5.0-1+deb12u5)
End-Date: 2026-10-23  06:04:36
```
(El upgrade de `libexpat1` fue automático y posterior al parche manual de PHP, no relacionado.)

### 2.4 unattended-upgrades.log (21–23 oct)
```
2026-10-21 06:13:15,880 INFO Packages that will be upgraded: ca-certificates
2026-10-21 06:13:23,241 INFO All upgrades installed
2026-10-22 06:10:44,802 INFO No packages found that can be upgraded unattended and no pending auto-removals
2026-10-23 06:04:35,177 INFO Packages that will be upgraded: libexpat1
2026-10-23 06:04:36,753 INFO All upgrades installed
```
Confirma que unattended-upgrades **no** aplicó el parche de PHP — fue intervención manual del admin, como muestra el history.log.

### 2.5 Hora de arranque del proceso maestro de FPM
```
$ ps -o pid,lstart,cmd -C php-fpm8.2
    PID                  STARTED CMD
 129022 Fri Oct 23 00:35:00 2026 php-fpm: master process (/etc/php/8.2/fpm/php-fpm.conf)
 129024 Fri Oct 23 00:35:00 2026 php-fpm: pool www
 129025 Fri Oct 23 00:35:00 2026 php-fpm: pool www
```
Coincide con el minuto siguiente al fin de la actualización de paquetes (00:34:34) — confirma que el binario parcheado quedó cargado en memoria.

### 2.6 needrestart / procesos PHP de larga duración
```
$ sudo needrestart -b
sudo: needrestart: command not found
```
No está instalado en este host. Como sustituto, se confirmó que **no existen** workers de cola/scheduler de Laravel ni otros procesos PHP de larga duración:
```
$ ps aux | grep -i php
root      129022  ...  php-fpm: master process (/etc/php/8.2/fpm/php-fpm.conf)
www-data  129024  ...  php-fpm: pool www
www-data  129025  ...  php-fpm: pool www

$ systemctl list-units --all | grep -iE "php|queue|schedule|artisan"
php8.2-fpm.service        loaded active running   The PHP 8.2 FastCGI Process Manager
phpsessionclean.service   loaded inactive dead     Clean php session files
phpsessionclean.timer     loaded active  waiting   Clean PHP session files every 30 mins
```
Es decir: todo el PHP en ejecución son los dos workers del pool `www`, arrancados tras el parche. No hay nada más que reiniciar.

### 2.7 /etc/php/8.2/fpm/pool.d/ (directivas relevantes)
```
$ sudo ls -la /etc/php/8.2/fpm/pool.d/
-rw-r--r-- 1 root root 22096 Aug 10 16:44 www.conf

$ sudo grep -E "^(user|group|listen)" /etc/php/8.2/fpm/pool.d/www.conf
user = www-data
group = www-data
listen = /run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
```
Un solo pool (`www`), escucha por **socket Unix**, no TCP.

### 2.8 ss -ltnp
```
$ sudo ss -ltnp
LISTEN 0 128    0.0.0.0:22       0.0.0.0:*  sshd
LISTEN 0 511    0.0.0.0:80       0.0.0.0:*  nginx
LISTEN 0 511    0.0.0.0:443      0.0.0.0:*  nginx
LISTEN 0 128    [::]:22          [::]:*     sshd
LISTEN 0 511    [::]:80          [::]:*     nginx
LISTEN 0 511    [::]:443         [::]:*     nginx
(+ systemd-resolved en 127.0.0.53/54:53 y 5355)
```
Confirma que PHP-FPM **no** escucha en ningún puerto TCP. Solo 22, 80 y 443 están expuestos (por nginx/sshd).

### 2.9 Fuentes APT
```
$ cat /etc/apt/sources.list.d/debian.sources
Types: deb deb-src
URIs: mirror+file:///etc/apt/mirrors/debian.list
Suites: bookworm bookworm-updates bookworm-backports
Components: main

Types: deb deb-src
URIs: mirror+file:///etc/apt/mirrors/debian-security.list
Suites: bookworm-security
Components: main

$ cat /etc/apt/sources.list.d/nodesource.sources
Types: deb
URIs: https://deb.nodesource.com/node_20.x
Suites: nodistro
Components: main
```
Único repo externo: NodeSource (Node.js para el build de Vite), no relacionado con PHP. No hay repos PHP de terceros que retirar en este servidor.

### 2.10 Configuración de unattended-upgrades
```
$ cat /etc/apt/apt.conf.d/20auto-upgrades
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Unattended-Upgrade "1";

$ cat /etc/apt/apt.conf.d/50unattended-upgrades (orígenes permitidos)
Unattended-Upgrade::Origins-Pattern {
    "origin=Debian,codename=${distro_codename},label=Debian";
    "origin=Debian,codename=${distro_codename},label=Debian-Security";
    "origin=Debian,codename=${distro_codename}-security,label=Debian-Security";
};
```
Unattended-upgrades está activo e incluye el origen de seguridad — por eso sí habría llegado a aplicar el parche solo (de hecho ya había subido PHP 8.2.33→8.2.34 automáticamente el 6-oct), pero el salto final a 8.2.35 se adelantó manualmente el 23-oct, antes del ciclo automático de esa madrugada (06:04).

---

## 3. Preservación de evidencia (logs)

Copiados fuera del árbol de logs original, sin alterar los archivos fuente, en `/home/admin/evidencia-equinox27/logs/`. Pendiente indicarme la carpeta compartida final para moverlos.

```
$ sha256sum *.log
628be96b54a46bb866ea52c597a04ad10889e8d5aacfc667fd85c736b60fa16  journal-full-since-2026-10-17.log
188a1eb3eab82cb7dbd80e2fe878f6bcc77283c198b2fe56933405c193f6e9f  journal-php8.2-fpm-since-2026-10-17.log
fc2391432cd99a028d82e258a7c98df42156c4cc64fa30a7f6e78df9c5d390e  journal-sshd-since-2026-10-17.log
7035275847f37e4258e060aa68ca2afb3f6b54c6e42df14ee5073d5a0c84dc0  nginx-access.log
b8e82210fe8ecf84c2a50e5eafb3f563ea449b5732e8c2ee0cb802ee865ec06  nginx-error.log
2327e900d8f06e69caf8bb8b7b9ea458aafc0d305a5146b0dd2fae593492e64  nginx-modsec_audit.log
d2e70c8dea74f4e1b50596d5ede10018117896d7958a72015ead3099753b173  php8.2-fpm.log
```

Notas:
- No existe `/var/log/auth.log` en este host: no tiene `rsyslog` instalado, solo `journald`. El equivalente preservado es `journal-sshd-since-2026-10-17.log` (filtrado por `sshd`) y el journal completo del sistema.
- Ninguno de los logs de nginx/PHP-FPM había rotado aún (logrotate diario configurado a 14 días, pero no se había disparado todavía), así que ambos cubren desde el 4 de octubre — no hay pérdida de datos del 17-oct hacia atrás en este momento, pero sigue siendo urgente sacarlos antes de que la rotación empiece a truncar.
- También se incluyó `nginx-modsec_audit.log` (ModSecurity/OWASP CRS, no pedido explícitamente pero relevante para el punto 4) — este servidor tiene WAF activo.
- No se borró ni modificó ningún archivo original en el servidor.

---

## 4. Búsqueda de indicios de compromiso desde el 17-oct-2026

### 4.1 Campo PHP_SESSION_UPLOAD_PROGRESS / user-agent php2shell-scan
```
$ sudo awk '$4 >= "[17/Oct/2026"' /var/log/nginx/access.log | grep -iE "PHP_SESSION_UPLOAD_PROGRESS|php2shell-scan"
(sin resultados)

$ sudo grep -iE "PHP_SESSION_UPLOAD_PROGRESS|php2shell-scan" /var/log/nginx/error.log /var/log/nginx/modsec_audit.log
(sin resultados)
```
**Resultado: 0 coincidencias** en access.log, error.log y modsec_audit.log.

### 4.2 Ráfagas de respuestas 500/502
```
$ sudo awk '$9==500 || $9==502' /var/log/nginx/access.log
52.32.45.175    [04/Oct/2026:16:10:20] GET /        500  (visitante real, día de despliegue)
35.93.30.123    [04/Oct/2026:16:10:26] GET /        500
204.62.249.30   [04/Oct/2026:16:11:54] GET /        500  (archive.org_bot)
3.233.18.117    [04/Oct/2026:16:12:46] GET /, POST /join  500  (curl de pruebas propias)
149.50.194.126  [04/Oct/2026:16:19:28] GET /        502
149.50.194.126  [04/Oct/2026:16:19:38] GET /        502
```
Las 13 respuestas 500/502 de todo el histórico del log ocurrieron el **4 de octubre** (día de despliegue/pruebas iniciales). **Cero** respuestas 500/502 desde el 17-oct en adelante.

### 4.3 Mensajes de FPM sobre procesos hijos terminados por señal
```
$ sudo journalctl -u php8.2-fpm --since "2026-10-17" | grep -iE "signal|terminated|segfault"
(sin resultados)

$ sudo grep -iE "terminated by signal|exited on signal" /var/log/php8.2-fpm.log
(sin resultados)
```
Sin coincidencias.

### 4.4 Cambios en storage/, bootstrap/cache/, .env y raíz pública
```
$ sudo find storage bootstrap/cache -newer composer.json -type f -printf "%T@ %p\n" | sort -n
(todos los timestamps corresponden al despliegue inicial del 4-oct; el más reciente es
 bootstrap/cache/config.php y vistas compiladas de esa misma fecha)

$ sudo find public -newermt "2026-10-17" -type f
(sin resultados)

$ sudo ls -la .env
-rw-r-----+ 1 admin admin 1666 Oct  4 17:22 .env
```
Sin cambios en `storage/`, `bootstrap/cache/` ni en la raíz pública desde el 17-oct. `.env` sin modificar desde el despliegue inicial (4-oct, 17:22).

### 4.5 Crontab de www-data y del sistema
```
$ sudo crontab -u www-data -l
sudo: crontab: command not found

$ sudo ls -la /etc/cron.d/
certbot      (renovación de certificados, estándar)
e2scrub_all  (estándar Debian)
php          (limpieza de sesiones PHP, estándar)
```
No hay binario `crontab` instalado y no hay `/var/spool/cron/crontabs`. `/etc/cron.d` solo contiene entradas estándar de Debian/Certbot — nada propio de la aplicación ni sospechoso.

### 4.6 authorized_keys, usuarios nuevos y unidades systemd
```
$ sudo find / -maxdepth 4 -name authorized_keys -exec ls -la {} \;
-rw------- 1 admin admin 405 Oct  4 14:11 /home/admin/.ssh/authorized_keys
-rw------- 1 root  root  568 Oct  4 14:11 /root/.ssh/authorized_keys

$ awk -F: '$7 !~ /nologin|false/ {print}' /etc/passwd
root:x:0:0:root:/root:/bin/bash
admin:x:1000:1000:Debian:/home/admin:/bin/bash

$ sudo stat -c "%Y %n" /etc/passwd /etc/shadow
1791123081 /etc/passwd   (4-oct, alta inicial del sistema)
1791123081 /etc/shadow

$ sudo find /etc/systemd/system /lib/systemd/system -newermt "2026-10-17" -type f
/lib/systemd/system/php8.2-fpm.service   (reemplazado por la actualización del paquete, esperado)
```
`authorized_keys` sin modificar desde el alta inicial del servidor. Sin usuarios nuevos. Sin unidades systemd añadidas (solo el archivo de servicio de php8.2-fpm, reescrito por la propia actualización del paquete).

### 4.7 Conexiones salientes establecidas
```
$ sudo ss -tnp state established
172.26.4.134:80  <- 185.206.221.199   (nginx, visitante web legítimo en curso)
172.26.4.134:22  <- 181.199.46.139    (sshd, sesión administrativa en curso)
172.26.4.134:* -> 160.79.104.10:443 (x4), [...]:443 (x9)   (proceso "claude", esta misma sesión de trabajo)
```
Sin conexiones salientes anómalas. Las únicas salientes corresponden a este asistente (Claude Code) operando en la sesión actual.

### 4.8 Resumen del ruido de ataques bloqueado por ModSecurity (17-oct en adelante)
```
$ sudo awk '/^2026\/10\/1[789]/ || /^2026\/10\/2[0-9]/ {print}' /var/log/nginx/error.log | grep -ci modsecurity
2674

$ ... | grep -oE '\[id "[0-9]+"\]' | sort | uniq -c
   2670 [id "949110"]   (Anomaly Score Exceeded — bloqueo genérico)
      3 [id "200002"]
      1 [id "200003"]
```
2,674 solicitudes bloqueadas con 403 por el WAF desde decenas de IPs distintas (escaneo genérico de SQLi/XSS/paths comunes), **ninguna coincide** con los patrones específicos de PHP2Shell (sección 4.1). No se identificó explotación exitosa.

---

## 5. Inventario de infraestructura (Equinox)

```
$ aws sts get-caller-identity
Account: 442728149913
Arn: arn:aws:sts::442728149913:assumed-role/AmazonLightsailInstanceRole/i-08eec24368b0ecb7d

$ curl IMDS -> instance-id, placement/region, instance-type, ami-id, placement/availability-zone
instance-id:   i-08eec24368b0ecb7d
region:        us-east-1
AZ:            us-east-1a
instance-type: t3.micro
ami-id:        ami-0edc3d49ed1648c23
vpc-id:        vpc-0889e02e9a3f755a9
security-group-ids: sg-05fdef9c194f490e1, sg-0a0237b2f82cc0653, sg-0bddd7797e56a0255
security-groups (nombres):
  "Your Parkside Resources"
  "Ingress from Your Parkside LoadBalancers"
  "ps-adhoc-22(in:0.0.0.0/0)(in:::/0)_443(in:0.0.0.0/0)_80(in:0.0.0.0/0)(in:::/0)"
```
- **Sistema operativo:** Debian GNU/Linux 12 (bookworm)
- **Servicio:** el nombre del rol IAM (`AmazonLightsailInstanceRole`) y de los security groups indican que el backend real es **AWS Lightsail** (instancia respaldada por EC2), no EC2 contratado directamente.
- **Hallazgo de seguridad:** el nombre del security group `ps-adhoc-22(...)` codifica que el **puerto 22 (SSH) está abierto a 0.0.0.0/0 y ::/0**, junto con 80/443. Coincide con el ruido de escaneo SSH visto en el journal (bots probando login). Recomendación: restringir el 22 a IPs/rango conocidos.
- **php -v en este activo:** confirmado en sección 2.1 (sí corre PHP).

### 5.1 IMDSv2
```
$ curl -s -o /dev/null -w "%{http_code}" http://169.254.169.254/latest/meta-data/instance-id   (sin token)
200
```
IMDSv1 (sin token) **respondió 200** → el modo "solo IMDSv2 obligatorio" (`HttpTokens: required`) **no está forzado** en esta instancia. Es una debilidad real a corregir (debería exigirse token).

### 5.2 IAM, CloudTrail, GuardDuty — bloqueado por permisos
```
$ aws ec2 describe-instances --instance-ids i-08eec24368b0ecb7d
AccessDenied: ... not authorized to perform: ec2:DescribeInstances

$ aws cloudtrail describe-trails
AccessDenied: ... not authorized to perform: cloudtrail:DescribeTrails

$ aws guardduty list-detectors
AccessDenied: ... not authorized to perform: guardduty:ListDetectors

$ aws ec2 describe-snapshots --owner-ids self
UnauthorizedOperation: ... not authorized to perform: ec2:DescribeSnapshots
```
El rol de instancia (`AmazonLightsailInstanceRole`) **no tiene permisos** para consultar su propia configuración de metadatos, CloudTrail, GuardDuty ni snapshots desde dentro del servidor. **Esto no se puede confirmar desde Equinox**: se necesita acceso de cuenta AWS (consola o credenciales con permisos de lectura a nivel de cuenta) para:
  - Confirmar si `HttpTokens` está en `required` a nivel de configuración de instancia.
  - Confirmar si CloudTrail/GuardDuty están activos y si hubo alertas del 17 al 23 de octubre.
  - Confirmar si se tomó un snapshot de disco antes de parchear.

### 5.3 IP real / balanceador / Cloudflare
```
$ sudo grep -rn "real_ip\|set_real_ip\|proxy_protocol" /etc/nginx/
(sin resultados)

$ grep -i "cf-connecting-ip\|cloudflare" /etc/nginx/sites-available/equinox27.com /var/log/nginx/access.log
(sin resultados reales, solo un intento de escaneo de ruta "/.env.cloudflare")
```
No hay configuración de IP real en nginx y no hay evidencia de Cloudflare. Las IPs en access.log varían ampliamente (visitantes/bots reales), lo que indica que el tráfico llega directo a nginx (o a través de un NLB transparente que preserva la IP de origen, no un proxy que la reescriba).

---

## 6. Confirmaciones puntuales (alcance Equinox)

| Pregunta | Respuesta |
|---|---|
| Hora exacta y zona horaria del parche en Equinox | **Pendiente** — la confirma Antonio directamente (fuera de este informe). Dato crudo disponible: `apt-get` terminó a las 2026-10-23 00:34:34 según `history.log`, hora del sistema (zona horaria del servidor pendiente de confirmar explícitamente). |
| ¿equinox27.com es un sistema de HEKA o de un cliente? | Evidencia de código: es la landing page del evento *"EQUINOX — Where Space Meets at the Equator"* (16–19 marzo 2027, Quito), organizado por **Fundación Sideralis** (ver `README.md` del proyecto). El repositorio se llama `hekaEquinoxLandingPage` pero está en la cuenta GitHub personal `xzgdfzsgf`, no en `HEKA-REP`. Indicación técnica fuerte de que es un **proyecto de cliente** alojado/desarrollado por HEKA — requiere confirmación de negocio (contrato) para cerrarlo formalmente. |
| ¿Se tomó una instantánea de disco antes de parchear? | **No se pudo verificar** desde la instancia (sin permisos `ec2:DescribeSnapshots`, ver 5.2). Requiere acceso de cuenta AWS. |
| ¿Qué verificaciones hizo Sebastián como revisor? | No se encontró ninguna mención de "Sebastián" en el repositorio, commits ni documentación local (`git log --all --grep`, búsqueda en docs/). Esta confirmación debe venir directamente de él. |

---

## 7. Pendientes / limitaciones de este informe

1. **Carpeta compartida destino:** la evidencia preservada (sección 3) vive por ahora en `/home/admin/evidencia-equinox27/logs/` dentro del propio servidor — falta indicar dónde moverla/compartirla.
2. **Credenciales AWS de cuenta:** se necesitan permisos de lectura a nivel de cuenta (no del rol de instancia) para cerrar: estado real de `HttpTokens` (IMDSv2 forzado), CloudTrail, GuardDuty y alertas del 17–23 oct, y snapshots previos al parche.
3. **Hora/zona horaria del parche y verificación de Sebastián:** a completar por Antonio, fuera del alcance de este documento.
4. **Confirmación de negocio** sobre si equinox27.com es propiedad de HEKA o de Fundación Sideralis como cliente.

---

## 8. Conclusión

El servidor que alberga equinox27.com está actualizado a PHP 8.2.35 (paquetes `php8.2-*` en `8.2.35-1~deb12u1`, instalado manualmente el 2026-10-23 00:34:16–00:34:34, FPM reiniciado a las 00:35:00), lo que corrige CVE-2026-109618 y CVE-2026-109619. No se encontraron indicios de compromiso en los frentes revisados desde el 17 de octubre (sin patrones de explotación específicos, sin cambios no explicados en archivos de la app, sin usuarios/claves/cron/systemd nuevos, sin conexiones salientes anómalas, sin ráfagas de error post-17-oct). Los logs relevantes quedaron preservados con hash SHA-256 sin alterar los originales. Quedan abiertos los puntos que requieren acceso a nivel de cuenta AWS (CloudTrail, GuardDuty, snapshots, forzado de IMDSv2) y las confirmaciones que corresponden directamente a Antonio y a Sebastián (horas/zona horaria y revisión).
