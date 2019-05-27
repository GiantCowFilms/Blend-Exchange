module.exports = function (api) {
  api.cache(true);
  return {
    "presets": [
      ["@babel/preset-env", {
        "targets": {
            "chrome": "49",
            "firefox": "54",
            "ie": "11"
        }
      }],
    ],
    "plugins": [
      "@babel/plugin-proposal-object-rest-spread"
    ]
  };
}

