import './bootstrap';

// 1. Importar Alpine.js
import Alpine from 'alpinejs';

// 2. Hacer Alpine global (para que funcione en las vistas Blade)
window.Alpine = Alpine;

// 3. Iniciar Alpine
Alpine.start();
