document.addEventListener('ffReady', function () {
    const redirectPath = window.ffRedirectPath || '/factfinder/result';

    document.addEventListener('before-search', function (event) {
        if (['productDetail', 'getRecords'].lastIndexOf(event.detail.type) === -1) {
            event.preventDefault();
            window.location = redirectPath + factfinder.common.dictToParameterString(factfinder.common.encodeDict(event.detail));
        }
    });

    document.addEventListener('dom-updated', function () {
        document.querySelectorAll('.ff-suggest-parent-category').forEach(function (el) {
            if (el.innerText.trim() === '>') {
                el.style.display = 'none';
            } else {
                el.style.display = 'inline-block';
            }
        });
    });
});
