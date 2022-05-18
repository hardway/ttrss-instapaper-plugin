function shareArticleToInstapaper(id) {
    try {
        var query = "?op=pluginhandler&plugin=instapaper&method=getInfo&id=" + encodeURIComponent(id);

        var w = window.open('about:blank', 'ttrss_instapaper',
            "status=0,toolbar=0,location=0,width=500,height=450,scrollbars=1,menubar=0");

        xhr.json("backend.php", App.getPhArgs("instapaper", "getInfo", {id: id}), (r) => {
            var share_url = "http://www.instapaper.com/hello2?" + "url=" + encodeURIComponent(r.link) + "&title=" + encodeURIComponent(r.title);
            w.location.href = share_url;
        });
    } catch (e) {
        console.error("[ttrss-instapaper-plugin]", e);
    }
}

require(['dojo/_base/kernel', 'dojo/ready'], function  (dojo, ready) {
    ready(function () {
        App.hotkey_actions['instapaper']=() => {
            const id = Article.getActive();
            if (id) {
               shareArticleToInstapaper(id);
            }
         };
    });
});
