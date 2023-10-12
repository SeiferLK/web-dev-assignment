@props(['author' => null])

<!-- For existing authors -->
<input type="hidden" id="author_id" name="author_id" value="{{ $author?->id }}" />

<x-text-input type="text" id="author" name="search" list="author_list" autocomplete="off"
    placeholder="Search for an author" value="{{ $author?->name ?? '' }}" />

<datalist id="author_list">
    <!-- Select is a workaround for older versions of Safari -->
    <select name="author" id="author_select">
        @if ($author)
            <option value="{{ $author->name }}" data-id="{{ $author->id }}"></option>
        @endif
        <!--JS code will add / change options here-->
    </select>
</datalist>

<script>
    const Cache = {
        set: new Set(),
        add(fetchedArray) {
            this.set = new Set([...this.set, ...fetchedArray]);
        },
        search(query) {
            let results = [];
            this.set.forEach((value) => {
                if (value.name.toLowerCase().startsWith(query.toLowerCase())) {
                    results.push(value);
                }
            });
            return results;
        },
    }

    const fetchData = async (searchString) => {
        const query = new URLSearchParams({
            query: searchString
        })
        const URL = window.location.origin + "/api/search/authors?" + query;
        const response = await fetch(URL, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
            }
        });
        return response.json();
    };

    const search = async (e) => {
        const val = e.target.value.trim();
        if (!val) return;
        // Search in the cache for values that start with the typed value.
        const results = Cache.search(val);
        if (results.length > 0) {
            return;
        }
        // Otherwise, search in the server.
        const apiResults = await fetchData(val);
        if (apiResults.length > 0) {
            console.warn("Fetched from server");
            Cache.add(apiResults);
            renderOptions(Cache.set);
        }
    };

    const renderOptions = (options) => {
        if (options.size > 0) {
            const frag = document.createDocumentFragment();
            options.forEach((item) => {
                const option = document.createElement("option");
                option.value = item.name;
                option.dataset.id = item.id;
                frag.appendChild(option);
            });
            if (frag.hasChildNodes()) {
                const sel = document.getElementById("author_select");
                const newSel = sel.cloneNode(false); // clone Select without options.
                newSel.appendChild(frag);
                sel.parentNode.replaceChild(newSel, sel);
            }
        }
    };

    const debounce = (func, wait = 250) => {
        let timeout;
        return function(event) {
            // console.log(`debounced ${func.name} called`)
            clearTimeout(timeout);
            timeout = setTimeout(() => func(event), wait);
        };
    };

    const input = document.getElementById("author");

    if (input) {
        input.addEventListener("keyup", debounce(search));
        input.focus();
    }

    document.querySelector('input[list]').addEventListener('input', function(e) {
        const val = e.target.value;
        const selectedOption = document.querySelector(`#author_select option[value='${val}']`);
        const authorIdField = document.querySelector("#author_id");

        if (selectedOption) {
            authorIdField.value = selectedOption.dataset.id;
        } else {
            // Unset the field if the user hasn't selected a valid author
            authorIdField.value = "";
        }
    });
</script>
