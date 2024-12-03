import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

const host = process.env.VITE_HOST || "10.200.2.64";
const port = process.env.VITE_PORT || 5173;

export default defineConfig({
	plugins: [
		laravel({
			input: ["resources/scss/app.scss", "resources/js/app.js"],
			refresh: true,
		}),
	],
	server: {
		host: "0.0.0.0",
		hmr: {
			host: host,
			protocol: "ws",
		},
		watch: {
			usePolling: true,
		},
		port: port,
	},
	base: `http://${host}:${port}/`,
});
