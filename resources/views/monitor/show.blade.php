<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor - {{ ucfirst($type) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">
    <div class="container mx-auto py-10">
        <h1 class="text-4xl font-bold text-center mb-8">📺 Monitor de {{ ucfirst($type) }}</h1>

        {{-- En Atención --}}
        <div class="mt-10">
            <h2 class="text-2xl font-semibold mb-6 text-center">📋 Turnos</h2>
            <div id="ultimos" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-gray-300">Cargando...</div>
            </div>
        </div>

        {{-- Últimos atendidos --}}




    </div>

    <script>
        async function fetchStatus() {
            try {
                const res = await fetch("{{ route('status', $type) }}");
                const data = await res.json();

                // Últimos atendidos + actual
                const ultimosEl = document.getElementById("ultimos");
                ultimosEl.innerHTML = "";
                if (data.ultimos.length > 0) {
                    data.ultimos.forEach((num, idx) => {
                        const card = document.createElement("div");
                        card.className =
                            "flex flex-col rounded-lg bg-slate-800 shadow-sm max-w-96 p-8 my-6 border border-slate-600";
                        card.innerHTML = `
                <div class="pb-8 m-0 mb-8 text-center text-slate-100 border-b border-slate-600">
                    <p class="text-sm uppercase font-semibold text-slate-300 mb-2">
                        ${idx === data.ultimos.length - 1 ? "👉 En atención" : "✅ Atendido"}
                        </p>
                        <h1 class="text-4xl font-bold text-white">${num}</h1>
                        </div> 
                `;
                        ultimosEl.appendChild(card);
                    });
                } else {
                    ultimosEl.innerHTML = "<p class='text-gray-300'>Aún no hay turnos</p>";
                }

            } catch (e) {
                console.error("Error al obtener estado", e);
            }
        }


        // Ejecutar al inicio y luego cada 5s
        fetchStatus();
        setInterval(fetchStatus, 5000);
    </script>
</body>

</html>
