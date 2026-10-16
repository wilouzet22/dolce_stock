# Regla Obligatoria: Ejecución y Actualización de GitHub tras Cambios

Siempre que se realice una modificación en los archivos del proyecto, la IA debe:

1. **Generar el comando o URL para ejecutar y verificar el cambio**:
   - Indicar el comando CLI correspondiente o la URL en el navegador local (`http://localhost/dolce_stock0.1/...`).
2. **Generar los comandos de Git para sincronizar con GitHub**:
   - Proveer los comandos `git add .`, `git commit -m "..."` y `git push origin main` con un mensaje descriptivo del cambio realizado.
   - En Windows PowerShell, si `git` no está reconocido en la sesión activa, indicar refrescar `$env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")` o usar `& "C:\Program Files\Git\cmd\git.exe"`.
