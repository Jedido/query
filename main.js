/**
 * Name: Jed Chen
 * Date: May 31, 2019
 * Section: CSE 154 AG
 * This is the JS to implement the Query game on the website.
 */

(function() {
    "use strict";

    const URL = "query.php";
    let queries = 0;
    let steps = 0;
    let passwords = {};
    window.addEventListener("load", init);

    /**
     * Helper function to do getElementById.
     * @param {string} name - id to look for
     * @returns {Node} the thing with the given id
     */
    function id(name) {
        return document.getElementById(name);
    }

    /**
     * Helper function to do querySelectorAll.
     * @param {string} name - thing to query for
     * @returns {Node[]} things that were selected
     */
    function qsa(name) {
        return document.querySelectorAll(name);
    }

    /** Initializes the event listeners */
    function init() {
        id("query-btn").addEventListener("click", query);
        id("password-btn").addEventListener("click", submit);
        id("reset-btn").addEventListener("click", reset);
    }

    /** Submit a password to the server. */
    function submit() {
        id("prompt").innerText = "Loading";
        let data = new FormData();
        data.append("password", id("password").value);
        id("password").value = "";
        fetch(URL, { method: "POST", body: data })
            .then(checkStatus)
            .then(JSON.parse)
            .then(handleSubmit)
            .catch(handlePasswordError);
    }

    /** Makes a query and sends it to the server. */
    function query() {
        id("prompt").innerText = "Loading";
        let data = new FormData();
        data.append("query", true);
        qsa("#query input").forEach(function(input) {
            if (input.id in passwords) {
                data.append(passwords[input.id], input.value);
            }
        });
        fetch(URL, { method: "POST", body: data })
            .then(checkStatus)
            .then(JSON.parse)
            .then(handleQuery)
            .catch(handleQueryError);
    }

    /** Sets query values back to their default. */
    function reset() {
        id("SELECT").value = "*";
        id("FROM").value = "Names";
        id("WHERE").value = "1";
        id("ORDER-BY").value = "1";
        id("LIMIT").value = 3;
    }

    /**
     * Handle success response of a password post request.
     * @param {JSON} json - the data returned by the API
     */
    function handleSubmit(json) {
        steps++;
        let prompt = id("prompt");
        prompt.innerText = json.message;
        prompt.classList.remove("hidden");
        if (json.key === "WIN") {
            prompt.innerText += "\nNumber of password submissions: " + steps;
            prompt.innerText += "\nNumber of queries: " + queries;
            id("password-view").classList.add("hidden");
        } else {
            id(json.key).disabled = false;
            passwords[json.key] = json.post;
        }
    }

    /**
     * Handle success response of a query, displaying all information in a table.
     * @param {JSON} json - the JSON data returned by the API
     */
    function handleQuery(json) {
        queries++;
        let table = id("table");
        id("prompt").classList.add("hidden");
        table.innerHTML = "";
        let tr = document.createElement("tr");
        json.columns.forEach(function(name) {
            let th = document.createElement("th");
            th.innerText = name;
            tr.appendChild(th);
        });
        table.appendChild(tr);
        for (let i = 0; i < json.rows; i++) {
            let row = document.createElement("tr");
            json.columns.forEach(function(name) {
                let td = document.createElement("td");
                td.innerText = json[name][i];
                row.appendChild(td);
            });
            table.appendChild(row);
        }
    }

    /**
     * Given helper for fetch; Handles the status of a promise
     * @param {Promise} response - the status from the API
     * @return {string} the response data from the server
     */
    function checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response.text();
        } else {
            return Promise.reject(new Error(response.status + ": " + response.statusText));
        }
    }

    /** Handle Query error given by AJAX request. */
    function handleQueryError() {
        id("prompt").innerText = "Error: No results. Make sure your query has no spaces and is well formed.";
        id("prompt").classList.remove("hidden");
    }

    /** Handle submit error given by AJAX request. */
    function handlePasswordError() {
        id("prompt").innerText = "Nothing happened.";
        id("prompt").classList.remove("hidden");
    }

})();