module.exports = {
    root: true,
    env: {
        node: true,
    },
    extends: [
        "plugin:vue/recommended", // enable recommended rules
        "@vue/airbnb",
        "@vue/typescript",
        "plugin:prettier/recommended",
    ],
    rules: {
        "import/no-extraneous-dependencies": [
            "error",
            {
                devDependencies: true,
            },
        ],
    },
    parserOptions: {
        parser: "@typescript-eslint/parser",
        sourceType: "module",
        ecmaVersion: 2018,
    },
};
