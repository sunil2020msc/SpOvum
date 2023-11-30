<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
</head>

<body>

    <div id="graph1"></div>
    <div id="graph2"></div>
    <div id="graph3"></div>
    <div id="graph4"></div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {

            graphs = ['colA VS sno(horizontal axis)', 'colB VS sno (horizontal axis)', 'Average(colA) VS date', 'Average(colB) VS date']

            graphs.map((graph, index) => {
                getGraph(graph, index + 1);
            })


        })

        const getGraph = (graphName, index) => {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    graph: 'graph' + index
                },
                beforeSend: () => {
                    $(`#graph${index}`).html("Loading..")
                },
                success: response => {
                    console.log(response)
                    graph = ` <h1> ${index}. ${graphName}
</h1> <canvas id="g${index}" style="width:100%;max-width:700px"></canvas>`
                    $(`#graph${index}`).html(graph)

                    const res = JSON.parse(response)
                    // console.log(res)
                    plotGraph('g' + index, res.xAxis, res.yAxis);
                },
                error: err => {
                    console.log(err)
                }
            })
        }


        plotGraph = (id, x, y) => {

            const xValues = x;
            const yValues = y;
            const barColors = []
            x.map(i => {
                barColors.push(getRandomColor())
            })


            // const barColors = ["red", "green", "blue", "orange", "brown"];

            new Chart(id, {
                type: "bar",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues
                    }]
                },

            });
        }

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
</body>

</html>