import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/css/app.css",
                "resources/js/custom-editor/editor.js",
                "resources/css/custom-editor/editor.css",
                "resources/js/create-assessment/assessment.js",
                "resources/css/assessment-take/index.css",
                "resources/css/create-assessment/assessment.css",
                "resources/js/assessment-take/index.js",
                "resources/js/courseview/attendance.js",
                "resources/js/courseview/course.js",
                "resources/js/courseview/leaderboard.js",
                "resources/js/courseview/lessonview.js",
                "resources/js/create-assessment/certify.js",
                "resources/js/custom-editor/editor.js",
                "resources/js/helpers.js",
                "resources/js/bootstrap.js",
                "resources/js/echo.js",
                "resources/js/utils.js",
                "resources/css/Main/main.css",
                "resources/css/Main/main.js",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
