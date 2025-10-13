$(document).ready(function () {
    //search product function

    const $searchBox = $('#s');
    const $results = $('#search-results');
    // --- Set and get localstorage for searchHistory ---
    function saveToHistory(keyword) {
        if (!keyword) return;
        let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
        history = history.filter(k => k !== keyword);
        history.unshift(keyword);
        if (history.length > 10) history.pop();
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }

    function getHistory() {
        return JSON.parse(localStorage.getItem('searchHistory') || '[]');
    }

    // --- Render list suggesions ---
    function renderList(items) {
        const currentPath = window.location.pathname;
        if (items.length !== 0) {
            let html = items.map(i => `<li><a href='${currentPath}?s=${encodeURIComponent(i)}' class="search-item">${i}</a></li>`).join('');
            $results.html(html).show();

        } else if (!items || items.length === 0) {

            $results.hide();
            return;
        }
    }

    $(document).on('click', '#search-results li a', function () {

        const keyword = $(this).text().trim();
        if (!keyword) return;

        let history = JSON.parse(localStorage.getItem('searchHistory')) || [];
        history = history.filter(k => k !== keyword);
        history.unshift(keyword);
        if (history.length > 10) history.pop();
        localStorage.setItem('searchHistory', JSON.stringify(history));

    });

    // --- when input search click ---
    $searchBox.click(function (e) {
        e.stopPropagation()
    })
    function handleSearch(query) {
        query = query.trim();

        if (query.length === 0) {
            renderList(getHistory());
            return;
        }

        $.get(SEARCH_SUGGESTIONS_URL, { q: query, category_id: category_id }, function (res) {
            if ($searchBox.val().trim() !== query) return; // avoid race condition

            if (res.data && res.data.length > 0) {
                renderList(res.data);
            } else {
                const filteredHistory = getHistory().filter(item =>
                    item.toLowerCase().includes(query.toLowerCase())
                );
                renderList(filteredHistory); // fallback history
            }
        });
    }

    // --- Typing ---
    $searchBox.on('input', function () {
        handleSearch($(this).val());
    });

    // --- Focus ---
    $searchBox.on('focus', function () {
        handleSearch($(this).val());
    });

    // --- when click on a suggesion ---
    $("form").on('submit', function () {
        const keyword = $searchBox.val();
        $searchBox.val(keyword);
        saveToHistory(keyword);
        // window.location.href = '/search?query=' + encodeURIComponent(keyword);
    });
    // code to handle when clicking on document
    $('html').click(function () {
        $results.hide();
    })
})
