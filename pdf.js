window.onload = function () {
    document.getElementById("download")
        .addEventListener("click", () => {
            const web = this.document.getElementById("web");
            console.log(web);
            console.log(window);
            var opt = {
                margin: 1,
                filename: 'myfile.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(web).set(opt).save();
        })


}
