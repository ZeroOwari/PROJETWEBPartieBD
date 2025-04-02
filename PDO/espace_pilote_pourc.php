<?php
include("Count.php");

// Prepare the data for the chart
$dataSets = [
    "all" => [getHasInternshipCount(), getStudentCount()-getHasInternshipCount()],
    "btp" => [getHasInternshipCountByPromo("BTP"), getStudentCountByPromo("BTP")-getHasInternshipCountByPromo("BTP"),],
    "informatique" => [getHasInternshipCountByPromo("Informatique"), (getStudentCountByPromo("Informatique")-getHasInternshipCountByPromo("Informatique"))],
    "generaliste" => [getHasInternshipCountByPromo("Généraliste"), getStudentCountByPromo("Généraliste")-getHasInternshipCountByPromo("Généraliste")]
];
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Pass PHP data to JavaScript
    let dataSets = <?php echo json_encode($dataSets); ?>;

    console.log(dataSets); // Debugging: Check if the dataSets object is valid

    let currentFilter = "all";

    let ctx = document.getElementById('stageChart').getContext('2d');

    let stageChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Stage obtenu', 'Stage non obtenu'],
            datasets: [{
                data: dataSets[currentFilter],
                backgroundColor: ['#7B8EEE', '#5A1763']
            }]
        }
    });

    document.getElementById("filterBtn").addEventListener("click", function () {
        let filterContainer = document.getElementById("filterContainer");
        filterContainer.style.display = (filterContainer.style.display === "block") ? "none" : "block";
    });

    document.querySelectorAll(".filter").forEach(button => {
        button.addEventListener("click", function () {
            let filter = this.getAttribute("data-filter");
            stageChart.data.datasets[0].data = dataSets[filter];
            stageChart.update();
        });
    });
});
</script>