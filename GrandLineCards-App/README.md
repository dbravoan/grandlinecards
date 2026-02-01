# 📱 Grand Line Cards - Mobile App (Ionic)

Aplicación móvil híbrida construida con **Ionic 8** y **Vue 3**, diseñada para ser el compañero perfecto en torneos presenciales.

## 🛠️ Stack Tecnológico
*   **Framework**: Ionic + Capacitor.
*   **UI Library**: Ionic Components + Tailwind CSS.
*   **State**: Vue Composition API.
*   **HTTP**: Axios (consumiendo `GrandLineCards-Web` API).

## 🎨 Tema y Diseño
El tema "Nautical" está configurado en `src/theme/variables.css`.
*   **Primary**: Gold/Amber (`#f59e0b`) - Usado para acciones principales y rarezas altas.
*   **Secondary**: Deep Ocean (`#0f172a`) - Fondo principal (Dark Mode by default).

## 📲 Compilación Nativa

Para generar las apps nativas (Android/iOS), necesitas tener instalado Android Studio o Xcode.

### Android
```bash
# 1. Build del proyecto web
npm run build

# 2. Sincronizar con Capacitor
npx cap sync android

# 3. Abrir en Android Studio
npx cap open android
```

### iOS
```bash
npm run build
npx cap sync ios
npx cap open ios
```

## 🔌 Configuración de API
Por defecto, la app apunta a `http://localhost/api/v1` para desarrollo.
Para producción, edita `src/services/api.js` y cambia la `baseURL`.

> **Nota para Android Emulator**: Si usas el emulador de Android, `localhost` apunta al propio emulador. Debes usar `10.0.2.2` para acceder al localhost de tu máquina.
