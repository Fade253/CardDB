<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Archetypes with js</title>

        <link rel="stylesheet" href="Style.css">
        <script src="main.js"></script>
    </head>
    <body>
        <h1>Archetypes but with js</h1>

        <button id="GetButton">Get Data</button>
        <br>
        <button id="AddButton">Add</button>
        <button id="DeleteButton" disabled>Delete</button>
        <button id="UpdateButton" disabled>Update</button>

        <div id="AddUpdatePanel">

            <div>
                <div class="formLabel">ID</div><input id="itemID" type="number">
            </div>
            <div>
                <div class="formLabel">Name</div><input id="name" type="text">
            </div>
            <div>
                <div class="formLabel">Category</div>
                <select id="Rarity">
                    <option value="UR">Ultra rare</option>
                    <option value="SR">Super Rare</option>
                    <option value="R">Rare</option>
                    <option value="N">Normal</option>
                </select>
            </div>
            <div>
                <div class="formLabel">Difficulty</div><input id="difficulty" type="number">
            </div>
            <div>
                <div class="formLabel">Description</div><input id="description" type="text">
            </div>
            <div>
                <button id="DoneButton">Done</button>
                <button id="CancelButton">Cancel</button>
            </div>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Rarity</th>
                <th>Difficulty/10</th>
                <th>Description</th>
            </tr>
        </table>

    </body>
</html>
