function Language() {
    this.default = null;
    this.loaded = [];
    this.translate = function (word, lang) {
        var lng = lang || this.default;
        if (this.loaded.indexOf(lng) === -1) {
            include('langs/' + lng, 'lang-' + lng, function () {
                Language.loaded.push(lng);
            });
            return 'translation failed';
        }
        var translated = Lang[lng][word];
        return translated ? translated : ' "' + word + '" not found';
    }
}
Language = new Language();