<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedMeal</title>
    <link rel="icon" href="images\titleLogo.png" type="image/png" sizes="19x19">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Noto+Sans+Sinhala&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">


    <script>

        function navigateToSignIn() {
            window.location.href = 'signin.html';
        }
        function setActiveSearchButton(buttonId) {
            document.querySelectorAll('.search-option').forEach(btn => {
                btn.classList.toggle('active', btn.id === buttonId);
            });
        }

        function showText() {
            document.getElementById("diseaseBox").style.display = "block";
            document.getElementById("additionalFilters").style.display = "block";
            document.getElementById("diseaseCombo").style.display = "none";
            setActiveSearchButton('textBtn');
        }

        function showCombo() {
            document.getElementById("diseaseCombo").style.display = "block";
            document.getElementById("additionalFilters").style.display = "block";
            document.getElementById("diseaseBox").style.display = "none";
            setActiveSearchButton('comboBtn');
        }

        let diseases = [];

function AddDisease() {

    const textValue = document.getElementById("diseaseInput").value.trim();
    const comboValue = document.getElementById("diseaseComboBox")?.value; // your combo box ID

    let finalValue = "";

    // ✅ choose which one to use
    if (textValue !== "") {
        finalValue = textValue;
    } else if (comboValue !== "") {
        finalValue = comboValue;
    } else {
        alert("Please enter or select a disease");
        return;
    }

    // ✅ prevent duplicates
    if (diseases.includes(finalValue)) return;

    // ✅ add to array
    diseases.push(finalValue);

    // ✅ create UI chip
    let newItem = document.createElement("div");
    newItem.className = "disease-item";

    newItem.innerHTML = `
        ${finalValue}
        <span onclick="removeDisease('${finalValue}', this)">✖</span>
    `;

    document.getElementById("diseaseList").appendChild(newItem);

    // clear input
    document.getElementById("diseaseInput").value = "";
    document.getElementById("diseaseComboBox").value = "";

    // update description
    diseaseDescription();
}

function handleDiseaseInputKey(event) {
    if (event.key === 'Enter' && diseases.length === 0) {
        event.preventDefault();
        AddDisease();
    }
}

function removeDisease(disease, element) {
    diseases = diseases.filter(d => d !== disease);
    element.parentElement.remove();
    diseaseDescription();
}

// function getMealPlan() {
//    //check if user added any diseases
//    if(diseases.length===0){
//     alert("please add antleast one disease!");
//     return;
//    }

//    document.getElementById("results").innerHTML = "⏳ Finding best foods for you...";

//    const diseaseString=diseases.join(',');

//    fetch('get_food.php?diseases=${diseaseString}')
//    .then(response => response.json())
//    .then(data => {

//     let goodList = data.good.map(food => `<li>${food}</li>`).join('');
//     let badList = data.bad.map(food => `<li>${food}</li>`).join('');
//     document.getElementById("results").innerHTML = `
//     <h3>🟢 Foods You SHOULD Eat:</h3>
//                 <ul>${goodList}</ul>
//     <h3>🔴 Foods You Should AVOID:</h3>
//                 <ul>${badList}</ul>
//     `;
//    })

//     .catch(error => {
//             document.getElementById("results").innerHTML = 
//                 "❌ Something went wrong! Check PHP is running.";
//         });
// }

function diseaseDescription() {
    const VegORnonVeg = document.getElementById("VegORnonVeg").value;
    const Age_Group = document.getElementById("Age_Group").value;
    const allergies = document.getElementById("allergies").value;

    if (diseases.length > 0 && Age_Group !== '') {
        const diseaseList = diseases.join(', ');
        const description = `Diseases: ${diseaseList} \n Age Group: ${Age_Group}\n Diet: ${VegORnonVeg || 'None'}\n Allergies: ${allergies || 'None'}`;
        document.getElementById("diseaseDescription").innerText = description;
        document.getElementById("diseaseDescription").style.display = "block";

        //getting recommendations based on the description
        // getMealPlan();
    } else {
        document.getElementById("diseaseDescription").innerText = '';
        document.getElementById("diseaseDescription").style.display = "none";

        //hide results when description hides too
        document.getElementById("results").innerHTML = '';
    }
}
    </script>
</head>

<body>
    <nav class="login-btn">
    <?php if (isset($_SESSION['user_name'])): ?>
        <!-- Show user name -->
        <p class="user-name">
         👤<?php echo $_SESSION['user_name']; ?>
        </p>
    <?php else: ?>
        <!-- Show sign in button -->
        <button type="button" id="signin" onclick="navigateToSignIn()">
        </button>
    <?php endif; ?>
    </nav>
     <!-- <button type="button" id="signin" onclick="navigateToSignIn()">     -->
    <div class="container">
        <h1><img class="logo" src="images/logo.png" alt="MedMeal Logo"><span class="medi">Medi</span><span class="meal">ආහාර</span></h1>
        <p>Personalized Sri Lankan meal plans for better health.</p>
        <p class="sinhala">ඔබගේ සෞඛ්‍යයට ගැළපෙන ආහාර සැලසුම්</p>
    </div>
    <div class="form-group">
        <p>How would you like to search?</p>
        <div id="search_options">
        <button type="button" class="search-option" id="comboBtn" onclick="showCombo()">Select from menu</button>
        <button type="button" class="search-option" id="textBtn" onclick="showText()">Text Box</button>
        </div>
        <!-- <br><br> -->
     <div  id="user-inputs" class="input-box">
        <section id="diseaseBox" class="search-box" style="display: none;">
            <label for="diseaseInput">Search by disease or keyword:</label><br>
            <input id="diseaseInput" name="diseases[]" type="text" placeholder="Enter disease name or symptom..." oninput="diseaseDescription()" >
            <button type="button" id="AddDiseaseButton" onclick="AddDisease()"></button>
        </section>

        <section id="diseaseCombo" class="search-box" style="display: none;">
            <label for="diseaseInput">Select from combo box:</label><br>
            <select id="diseaseComboBox" name="diseases[]" onchange="diseaseDescription()" >
                <option value="">--Disease Type--</option>
                <option value="Dengue">Dengue ඩෙංගු டெங்கு</option>
                <option value="Malaria">Malaria මැලේරියා மலேரியா</option>
                <option value="Chikungunya">Chikungunya චිකුන්ගුනියා சிக்குன்குனியா</option>
                <option value="Tuberculosis">Tuberculosis (TB) ක්ෂය රෝගය காசநோய்</option>
                <option value="Influenza">Influenza (Flu) උණ காய்ச்சல்</option>
                <option value="Hepatitis">Hepatitis හෙපටයිටිස් ஹெபடைட்டிஸ்</option>
                <option value="Typhoid">Typhoid ටයිෆොයිඩ් டைபாய்டு</option>
                <option value="Leptospirosis">Leptospirosis (Rat fever) මී උණ எலி காய்ச்சல்</option>

                <option value="Diabetes">Diabetes දියවැඩියාව நீரிழிவு நோய்</option>
                <option value="High Blood Pressure">High Blood Pressure අධි රුධිර පීඩනය உயர் இரத்த அழுத்தம்</option>
                <option value="Heart Disease">Heart Disease හෘද රෝග இதய நோய்</option>
                <option value="Cancer">Cancer පිළිකා புற்றுநோய்</option>
                <option value="Asthma">Asthma ඇස්ථමා ஆஸ்துமா</option>
                <option value="Kidney Disease">Kidney Disease වකුගඩු රෝග சிறுநீரக நோய்</option>
                <option value="Stroke">Stroke ආඝාතය பக்கவாதம்</option>

                <option value="Common Cold">Common Cold සෙම්ප්‍රතිශ්‍යාව சளி</option>
                <option value="Fever">Fever උණ காய்ச்சல்</option>
                <option value="Diarrhea">Diarrhea දියවැඩි வயிற்றுப்போக்கு</option>
                <option value="Vomiting">Vomiting වමනය வாந்தி</option>
                <option value="Eye Infection">Eye Infection ඇස් රෝග கண் தொற்று</option>
                <option value="Ear Infection">Ear Infection කන් රෝග காது தொற்று</option>
            </select>
            <button type="button" id="AddDiseaseButton" onclick="AddDisease()"></button>
        </section>
        
        <section  class="additionalFilters" id="additionalFilters" style="display: none;">

            <select id="Age_Group" onchange="diseaseDescription()">
                <option value="">--Age Group--</option>
                <option value="child">Child ළමයා</option>
                <option value="teen">Teenager තරුණයා</option>
                <option value="adult">Adult වැඩිහිටි</option>
                <option value="senior">Senior මහලු</option>
            </select>

            <select id="VegORnonVeg" onchange="diseaseDescription()">
                <option value="">--Food Type--</option>
                <option value="Vegetarian">Vegetarian නිර්මාංශ</option>
                <option value="Non-Vegetarian">Non-Vegetarian මාංශ භක්ෂක</option>
            </select>
        
            <select id="allergies" onchange="diseaseDescription()">
                 <option value="">-- Select Allergy --</option>
                <option value="seafood">Seafood (මුහුදු ආහාර) (கடல் உணவு)</option>
                 <option value="eggs">Eggs (බිත්තර) (முட்டை)</option>
                 <option value="milk">Milk / Dairy (කිරි / කිරි නිෂ්පාදන) (பால் / பால் பொருட்கள்)</option>
                 <option value="peanuts">Peanuts (රටකජු) (வேர்க்கடலை)</option>
                 <option value="soy">Soy (සෝයා) (சோயா)</option>
                 <option value="coconut">Coconut (පොල්) (தேங்காய்)</option>
                 <option value="spices">Spices / Additives (මසාලා / එකතු කිරීම්) (மசாலா / சேர்க்கைகள்)</option>
            </select>
        </section>
     </div> 

        <div id="diseaseList"></div>
        <div id="diseaseDescription" class="description" style="display: none;"></div>
        <div id="results"></div>  

    </div>

    <footer>
        <p>&copy; 2026 MedMeal. All rights reserved.</p>
    </footer>
</body>

</html>