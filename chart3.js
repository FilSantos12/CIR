document.addEventListener("DOMContentLoaded", function () {
    fetch("dados_dashboard.php")
        .then(response => response.json())
        .then(data => {
            let imposto_pagar = 0, imposto_restituir = 0, valor_cobrado = 0;

            data.forEach(item => {
                imposto_pagar += parseFloat(item.imposto_pagar || 0);
                imposto_restituir += parseFloat(item.imposto_restituir || 0);
                valor_cobrado += parseFloat(item.valor_cobrado || 0);
            });

            let ctx = document.getElementById("bar").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Imposto a Pagar", "Imposto a Restituir", "Valor Cobrado"],
                    datasets: [{
                        label: "Valores Financeiros",
                        data: [imposto_pagar, imposto_restituir, valor_cobrado],
                        backgroundColor: ["#ff6384", "#36a2eb", "#4caf50"]// Cores diferentes para cada barra
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false, // Permite ajustar o tamanho dinamicamente
                 }
            });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
});
