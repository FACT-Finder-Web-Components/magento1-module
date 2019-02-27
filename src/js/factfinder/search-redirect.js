document.addEventListener("WebComponentsReady", function () {
    factfinder.communication.FFCommunicationEventAggregator.addBeforeDispatchingCallback(function (event) {
        if ((['search','navigation-search'].indexOf(event.type) !== -1) && window.location.href.match(/catalogsearch\/result/) === null) {
            let params = Object.assign({}, event);
            delete params.type;
            delete params.version;
            window.location.href = "/catalogsearch/result/" + factfinder.common.dictToParameterString(params);
        }
    });
});

document.addEventListener("dom-updated", function(){
    var parentCategory = document.getElementsByClassName('ff-suggest-parent-category');
    Array.prototype.forEach.call(parentCategory, function(el) {
        if (el.innerText == '>') {
            el.style.display = "none";
        } else {
            el.style.display = "inline-block";
        }
    });
});