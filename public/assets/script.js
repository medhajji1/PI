const searchBar=document.getElementById("search-bar");
const tableBody=document.getElementById("tableBody");
const tableBodyContent=Array.from(document.getElementById("tableBody").children);
const sortByName=document.getElementById("sortByName");

searchBar.addEventListener("keypress" , (event) => {
    if(event.key === 'Enter'){
    let newTableContent=Array.from(document.getElementById("tableBody").children);
    if(searchBar.value == ""){
        tableBody.innerHTML = '';
        tableBodyContent.forEach((row) => {
            tableBody.appendChild(row);
        })
    }else{
        tableBody.innerHTML = '';
        newTableContent.forEach((row) => {
            const criterias = row.id;
            console.log("criterias");console.log(criterias);
            console.log("searchValue");console.log(searchBar.value);
            console.log(criterias.includes(searchBar.value));

            if(criterias.includes(searchBar.value)){
                tableBody.appendChild(row);
            }
        })
    }
 }
 
})

sortByName.addEventListener("click", () => {
    let newTableContent=Array.from(document.getElementById("tableBody").children);
    const sortedTableContent = newTableContent.sort((a, b) => {
        const nameA = a.id.split(';')[1].toLowerCase();
        const nameB = b.id.split(';')[1].toLowerCase();
        if (nameA < nameB) return -1;
        if (nameA > nameB) return 1;
        return 0;
    });
    tableBody.innerHTML = '';
    sortedTableContent.forEach((row) => {
        tableBody.appendChild(row);
    });
});
