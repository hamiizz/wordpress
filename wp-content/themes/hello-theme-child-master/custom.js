(function ($) {
    $(document).ready(function () {
        var numQuotes = 5;
        var apiUrl = 'https://api.kanye.rest/';
        var quotesContainer = $('#kanye-quotes');

        function displayQuotes(quotes) {
            var html = '<ul>';
            for (var i = 0; i < quotes.length; i++) {
                html += '<li>' + quotes[i] + '</li>';
            }
            html += '</ul>';
            quotesContainer.html(html);
        }

        function getQuotes() {
            var requests = [];
            for (var i = 0; i < numQuotes; i++) {
                requests.push($.ajax({
                    url: apiUrl,
                    method: 'GET'
                }));
            }

            $.when.apply($, requests).done(function () {
                var quotes = [];
                for (var i = 0; i < arguments.length; i++) {
                    var response = arguments[i][0];
                    if (response && response.quote) {
                        quotes.push(response.quote);
                    }
                }
                displayQuotes(quotes);
            }).fail(function () {
                quotesContainer.html('<p>Failed to fetch quotes.</p>');
            });
        }

        getQuotes();
    });
})(jQuery);