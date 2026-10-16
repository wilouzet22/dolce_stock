# 🤖 Instrucciones y Reglas Obligatorias para la Inteligencia Artificial (AI Agent)

> **Repositorio:** `dolce_stock`  
> **Rama principal:** `main`  
> **Remote:** `https://github.com/wilouzet22/dolce_stock.git`  
> **Ámbito:** Aplica para cualquier asistente de IA (Antigravity, Claude, ChatGPT, Cursor, Copilot, Gemini, etc.) que trabaje en este proyecto.

---

## 📌 Protocolo Obligatorio Tras Cada Modificación

Cada vez que la IA realice una modificación, corrección de errores, agregado de funcionalidades o ajuste en los archivos del proyecto, **DEBE cumplir estrictamente con los dos pasos siguientes al finalizar su respuesta**:

---

### 1. 🚀 Comando / Instrucción de Ejecución y Verificación

La IA **siempre debe generar y presentar el comando o método exacto para ejecutar y comprobar el cambio** realizado.

- **Para cambios en la Web / Interfaz PHP:**
  - Indicar la ruta URL local exacta para abrir en el navegador:
    ```text
    http://localhost/dolce_stock0.1/index.php
    ```
  - Si aplica, indicar los pasos específicos de prueba en el navegador (ejemplo: recargar con `Ctrl + F5`, navegar al módulo correspondiente).
- **Para scripts o comandos de terminal (PowerShell / Bash / PHP CLI):**
  - Proporcionar el comando listo para ejecutar en la terminal (ejemplo: `php -l archivo.php`, ejecución de scripts o servicios).
- **Para modificaciones en la Base de Datos:**
  - Especificar el script o consulta SQL a ejecutar en phpMyAdmin o MySQL CLI.

---

### 2. 🐙 Sincronización Automática con GitHub

La IA **siempre debe generar los comandos de Git necesarios para actualizar el repositorio remoto en GitHub** con una descripción precisa de lo que se modificó.

La IA debe incluir el bloque de comandos con este formato:

```bash
git add .
git commit -m "<tipo>(<alcance>): <descripción clara del cambio en español>"
git push origin main
```

#### Convención de Commits a utilizar:
- `feat:` Nueva funcionalidad o módulo.
- `fix:` Corrección de errores o bugs.
- `refactor:` Refactorización o mejora de código sin cambiar funcionalidad.
- `style:` Ajustes visuales, CSS o diseño de interfaz.
- `docs:` Modificación de manuales, diagramas o documentación.
- `chore:` Tareas de mantenimiento o configuración.

#### Ejemplo de salida esperada de la IA:
```bash
git add .
git commit -m "feat(inventario): agregar validación de stock mínimo en productos"
git push origin main
```

*(Si la IA cuenta con herramientas para ejecutar comandos en el sistema del usuario, puede proponer o ejecutar dichos comandos directamente, verificando que los cambios se hayan enviado con éxito a GitHub).*

#### ⚠️ Nota para Windows / PowerShell:
Si la terminal arroja el error `El término 'git' no se reconoce como nombre de un cmdlet...`:
1. Actualizar el PATH de la sesión de PowerShell activa ejecutando:
   ```powershell
   $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
   ```
2. O invocar directamente el binario instalado:
   ```powershell
   & "C:\Program Files\Git\cmd\git.exe" <comando>
   ```
*(Nota: Ya se agregó permanentemente `C:\Program Files\Git\cmd` a las variables de entorno de Usuario).*

---

## 📋 Resumen del Flujo de Trabajo que la IA debe seguir

```text
[Recibir Tarea] 
      ⬇
[Implementar Cambios en Código / Archivos]
      ⬇
[1. Indicar Comando / URL para Ejecutar y Probar]
      ⬇
[2. Proveer / Ejecutar Comandos de Git para Actualizar GitHub]
```
