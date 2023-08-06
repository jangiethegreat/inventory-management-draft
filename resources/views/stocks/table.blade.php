<style>
    * {
        margin: 0;
        padding: 0;

        box-sizing: border-box;
        font-family: sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: url("{{ asset('background.jpg') }}");
        background-size: cover;
        background-repeat: no-repeat;

    }



    table,
    th,
    td {
        padding: 1rem;
        border-collapse: collapse;
        text-align: center;

    }

    main.table {
        width: 82vw;
        height: 90vh;
        background-color: #fff5;
        backdrop-filter: blur(7px);
        box-shadow: 0 .4rem .8rem #0005;
        border-radius: .8rem;
        overflow: hidden;
    }

    .table__header {
        width: 100%;
        height: 10%;
        background-color: #fff4;
        padding: .8rem 1rem;
    }

    .table__body {
        width: 95%;
        max-height: 89%;
        background-color: #fffb;
        margin: .8rem auto;
        border-radius: .6rem;
        overflow: auto;
        text-align: center;

    }

    .table__body::-webkit-scrollbar {
        width: 0.5rem;
        height: 0.5rem;
    }

    .table__body::-webkit-scrollbar-thumb {
        border-radius: 0.5rem;
        background-color: #0004;
        visibility: hidden;
    }

    .table__body:hover::-webkit-scrollbar-thumb {
        visibility: visible;
    }

    table {
        width: 100%;

    }

    thead th {
        position: sticky;
        top: 0;
        left: 0;
        background-color: #d5d1defe;

    }

    tbody tr:nth-child(even) {
        background-color: #0000000b;
    }

    tbody tr:hover {
        background-color: #fff6;
    }




    .status-brand-new {
        color: #008000;
        font-weight: bold;
    }





    .status-used {
        color: blue;
        font-weight: bold;
    }

    .status-for-repair {
        color: orange;
        font-weight: bold;
    }

    .status-defective {
        color: red;
        font-weight: bold;
    }
</style>


<main class="table">
    <section class="table__header">
        <h1>Table Header</h1>
    </section>
    <section class="table__body">
        <table id="customerTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1001</td>
                    <td>John Smith</td>
                    <td>32</td>
                    <td>john.smith@example.com</td>
                    <td>(555) 123-4567</td>
                    <td>123 Main St, Anytown, USA</td>
                    <td>Used
                    </td>
                </tr>
                <tr>
                    <td>1002</td>
                    <td>Jane Doe</td>
                    <td>28</td>
                    <td>jane.doe@example.com</td>
                    <td>(555) 987-6543</td>
                    <td>456 Elm Ave, Somewhereville, USA</td>
                    <td>Used</td>
                </tr>
                <tr>
                    <td>1003</td>
                    <td>Michael Johnson</td>
                    <td>40</td>
                    <td>michael.johnson@example.com</td>
                    <td>(555) 555-5555</td>
                    <td>789 Oak St, Nowhere City, USA</td>
                    <td>For Repair</td>
                </tr>
                <tr>
                    <td>1004</td>
                    <td>Emily Williams</td>
                    <td>24</td>
                    <td>emily.williams@example.com</td>
                    <td>(555) 222-3333</td>
                    <td>321 Pine Rd, Anytown, USA</td>
                    <td>Defective</td>
                </tr>
                <tr>
                    <td>1005</td>
                    <td>David Brown</td>
                    <td>35</td>
                    <td>david.brown@example.com</td>
                    <td>(555) 444-5555</td>
                    <td>555 Cedar Ln, Somewhereville, USA</td>
                    <td>Brand New</td>
                </tr>
                <tr>
                    <td>1006</td>
                    <td>Sarah Miller</td>
                    <td>29</td>
                    <td>sarah.miller@example.com</td>
                    <td>(555) 777-8888</td>
                    <td>777 Birch Rd, Nowhere City, USA</td>
                    <td>Used</td>
                </tr>
                <tr>
                    <td>1007</td>
                    <td>Robert Lee</td>
                    <td>42</td>
                    <td>robert.lee@example.com</td>
                    <td>(555) 111-2222</td>
                    <td>888 Maple Ave, Anytown, USA</td>
                    <td>For Repair</td>
                </tr>
                <tr>
                    <td>1008</td>
                    <td>Jessica Taylor</td>
                    <td>31</td>
                    <td>jessica.taylor@example.com</td>
                    <td>(555) 999-8888</td>
                    <td>999 Oak St, Somewhereville, USA</td>
                    <td>Defective</td>
                </tr>
                <tr>
                    <td>1009</td>
                    <td>William Wilson</td>
                    <td>27</td>
                    <td>william.wilson@example.com</td>
                    <td>(555) 777-1111</td>
                    <td>444 Elm Ave, Nowhere City, USA</td>
                    <td>Brand New</td>
                </tr>
            </tbody>
        </table>

    </section>
</main>


<script>
    // JavaScript code to apply dynamic styling to the "Status" field

    // Get the table element
    const table = document.getElementById('customerTable');

    // Loop through each row in the table (excluding the header row)
    for (let i = 1; i < table.rows.length; i++) {
        const statusCell = table.rows[i].cells[6]; // Assuming "Status" is the 7th cell (index 6)

        // Get the value of the "Status" cell
        const statusValue = statusCell.innerText.trim().toLowerCase();

        // Create a new span element
        const span = document.createElement('span');

        // Set the text of the span to the status value
        span.textContent = statusValue;

        // Apply the appropriate CSS class to the span based on the status value
        switch (statusValue) {
            case 'brand new':
                span.classList.add('status-brand-new');
                break;
            case 'used':
                span.classList.add('status-used');
                break;
            case 'for repair':
                span.classList.add('status-for-repair');
                break;
            case 'defective':
                span.classList.add('status-defective');
                break;
            default:
                // If status value doesn't match any known status, you can handle it here.
                break;
        }

        // Clear the content of the status cell
        statusCell.innerHTML = '';

        // Append the span element to the status cell
        statusCell.appendChild(span);
    }
</script>