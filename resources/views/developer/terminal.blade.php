<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Web Tinker</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #282c34;
            color: white;
            padding: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .container {
            display: flex;
            flex: 1;
            gap: 10px;
        }

        textarea {
            flex: 1;
            height: 100%;
            background: #1e1e1e;
            color: #00ff00;
            border: 1px solid #444;
            padding: 10px;
            font-size: 16px;
            outline: none;
            resize: none;
        }

        #output {
            flex: 1;
            height: 100%;
            background: #000;
            color: #0f0;
            border: 1px solid #444;
            padding: 10px;
            font-size: 16px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <h2>Laravel Web-Based Tinker</h2>

    <div class="container">
        <textarea id="command" placeholder="Enter Tinker command..."></textarea>
        <pre id="output"></pre>
    </div>

    <button onclick="runTinker()">Run</button>

    <script>
        async function runTinker() {
            let command = document.getElementById("command").value;

            if (!command.trim()) {
                alert("Please enter a command!");
                return;
            }

            // Split multi-line input into an array of commands
            let commands = command.split("\n").map(cmd => cmd.trim()).filter(cmd => cmd !== "");

            console.log("Sending commands:", commands); // Debugging output

            let response = await fetch("/run-tinker", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    commands
                }) // Send as an array
            });

            let result = await response.json();
            document.getElementById("output").innerText = result.output || result.error;
        }
    </script>

</body>

</html>
