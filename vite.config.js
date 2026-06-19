import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        // "resources/css/app.css",
        "resources/scss/app.scss", // <-- scss instead of css
        "resources/js/app.js",
      ],
      // refresh: ["resources/views/**", "routes/**"],
    }),
    {
      name: "blade",
      handleHotUpdate({ file, server }) {
        if (file.endsWith(".blade.php")) {
          server.ws.send({
            type: "full-reload",
            path: "*",
          });
        }
      },
    },
  ],
  server: {
    watch: {
      ignored: ["**/vendor/**", "**/storage/**", "**/bootstrap/cache/**"],
    },
  },
});
