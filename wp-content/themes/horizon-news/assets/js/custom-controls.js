(function(api) {

 const horizon_news_section_lists = ['flash-news', 'banner'];
 horizon_news_section_lists.forEach(horizon_news_homepage_scroll);

 function horizon_news_homepage_scroll(item, index) {
        // Detect when the front page sections section is expanded (or closed) so we can adjust the preview accordingly.
  item = item.replace(/-/g, '_');
  wp.customize.section('horizon_news_' + item + '_section', function(section) {
   section.expanded.bind(function(isExpanding) {
                // Value of isExpanding will = true if you're entering the section, false if you're leaving it.
    wp.customize.previewer.send(item, { expanded: isExpanding });
});
});
}

})(wp.customize);