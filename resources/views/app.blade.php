<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">.
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>URL Shortener</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body { font-family: sans-serif; padding: 2rem; }
    section { margin-bottom: 2rem; }
    input { width: 300px; padding: .5rem; }
    button { padding: .5rem 1rem; }
    pre { background:#f4f4f4; padding:1rem; }
  </style>
</head>
<body>

  <h1>URL Shortener</h1>
  <div id="output"><em>Result will appear here...</em></div>

  <section id="create">
    <h2>Create</h2>
    <input id="create-url" type="url" placeholder="Long URL">
    <button onclick="create()">Shorten</button>
  </section>

  <section id="retrieve">
    <h2>Retrieve &amp; Redirect</h2>
    <input id="retrieve-code" placeholder="Short code">
    <button onclick="retrieve()">Get &amp; Increment</button>
  </section>

  <section id="update">
    <h2>Update</h2>
    <input id="update-code" placeholder="Short code">
    <input id="update-url" type="url" placeholder="New long URL">
    <button onclick="updateUrl()">Update</button>
  </section>

  <section id="delete">
    <h2>Delete</h2>
    <input id="delete-code" placeholder="Short code">
    <button onclick="remove()">Delete</button>
  </section>

  <section id="stats">
    <h2>Stats</h2>
    <input id="stats-code" placeholder="Short code">
    <button onclick="stats()">Get Stats</button>
  </section>

  <script>
    function show(res) {
      document.getElementById('output').innerHTML =
        '<pre>'+ JSON.stringify(res, null, 2) +'</pre>';
    }
    function err(e) {
      show(e.response ? e.response.data : { error: e.message });
    }

    function create() {
      axios.post('/shorten', { url: document.getElementById('create-url').value })
        .then(r => show(r.data)).catch(err);
    }

    function retrieve() {
      axios.get('/shorten/'+document.getElementById('retrieve-code').value)
        .then(r => show(r.data)).catch(err);
    }

    function updateUrl() {
      axios.put(
        '/shorten/'+document.getElementById('update-code').value,
        { url: document.getElementById('update-url').value }
      ).then(r => show(r.data)).catch(err);
    }

    function remove() {
      axios.delete('/shorten/'+document.getElementById('delete-code').value)
        .then(() => show({ success: 'Deleted' })).catch(err);
    }

    function stats() {
      axios.get('/shorten/'+document.getElementById('stats-code').value+'/stats')
        .then(r => show(r.data)).catch(err);
    }
  </script>

</body>
</html>
