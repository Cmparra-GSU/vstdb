<?php
include('functions.php');
include('plugin-functions.php');
session_start();
isAdmin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create a New Plugin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header>
        <div class="container">
            <div class="navbar">
                <div class="logo">Create Plugin</div>
                <div class="button-group">
                    <a href ="admin.php" class = "button">Admin Panel</a>
                    <a href="../index/index.php" class="button">Index</a>
                    <a href="../index/logout.php" class="button">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <div class = main-content>
        <div class = "create-content">
    
            <div class = "createForm">
                <form id="pluginForm" method="POST" action="submit-plugin.php">

                    <div class = "formSection">
                        <label for="pluginName">Plugin Name:</label><br>
                        <input type="text" id="pluginName" name="pluginName" placeholder="Required" required><br>
                    </div>

                    <div class = "formSection">
                        <label for="developerName">Developer Name:</label><br>
                        <input type="text" id="developerName" name="developerName" placeholder="Required" required><br>
                    </div>
                    
                    <div class = "formSection">
                        <label for="developerSite">Developer Website:</label><br>
                        <input type="text" id="developerSite" name="developerSite"><br>
                    </div>
                    

                    <div class = "formSection">
                        <label for="type">Plugin Type:</label><br>
                        <select id="type" name="type" required>
                            <option value="Generator">Generator</option>
                            <option value="Effect">Effect</option>
                            <option value="Multi">Multi</option>
                        </select><br>
                    </div>

                    <div class="formSection">
                        <label>Categories:</label><br>
                        <div class = "checkbox-grid">
                        <label for="analog_synth"><input type="checkbox" id="analog_synth" name="categories[]" value="Analog Synth">Analog Synth</label><br>
                        <label for="arpeggiator"><input type="checkbox" id="arpeggiator" name="categories[]" value="Arpeggiator">Arpeggiator</label><br>
                        <label for="bass"><input type="checkbox" id="bass" name="categories[]" value="Bass">Bass</label><br>
                        <label for="chorus"><input type="checkbox" id="chorus" name="categories[]" value="Chorus">Chorus</label><br>
                        <label for="compressor"><input type="checkbox" id="compressor" name="categories[]" value="Compressor">Compressor</label><br>
                        <label for="delay"><input type="checkbox" id="delay" name="categories[]" value="Delay">Delay</label><br>
                        <label for="distortion"><input type="checkbox" id="distortion" name="categories[]" value="Distortion">Distortion</label><br>
                        <label for="drum_machine"><input type="checkbox" id="drum_machine" name="categories[]" value="Drum Machine">Drum Machine</label><br>
                        <label for="dynamic_eq"><input type="checkbox" id="dynamic_eq" name="categories[]" value="Dynamic EQ">Dynamic EQ</label><br>
                        <label for="effect"><input type="checkbox" id="effect" name="categories[]" value="Effect">Effect</label><br>
                        <label for="eq"><input type="checkbox" id="eq" name="categories[]" value="EQ">EQ</label><br>
                        <label for="filter"><input type="checkbox" id="filter" name="categories[]" value="Filter">Filter</label><br>
                        <label for="flanger"><input type="checkbox" id="flanger" name="categories[]" value="Flanger">Flanger</label><br>
                        <label for="FM"><input type="checkbox" id="FM" name="categories[]" value="FM">FM</label><br>
                        <label for="granular_synth"><input type="checkbox" id="granular_synth" name="categories[]" value="Granular Synth">Granular Synth</label><br>
                        <label for="guitar"><input type="checkbox" id="guitar" name="categories[]" value="Guitar">Guitar</label><br>
                        <label for="hardware_emulation"><input type="checkbox" id="hardware_emulation" name="categories[]" value="Hardware Emulation">Hardware Emulation</label><br>
                        <label for="limiter"><input type="checkbox" id="limiter" name="categories[]" value="Limiter">Limiter</label><br>
                        <label for="mastering"><input type="checkbox" id="mastering" name="categories[]" value="Mastering">Mastering</label><br>
                        <label for="midi"><input type="checkbox" id="midi" name="categories[]" value="MIDI">MIDI</label><br>
                        <label for="multi"><input type="checkbox" id="multi" name="categories[]" value="Multi">Multi</label><br>
                        <label for="utility"><input type="checkbox" id="utility" name="categories[]" value="Utility">Utility</label><br>
                        <label for="utility"><input type="checkbox" id="chiptune" name="categories[]" value="Chiptune">Chiptune/8bit</label><br>
                        <label for="orchestral"><input type="checkbox" id="orchestral" name="categories[]" value="Orchestral">Orchestral</label><br>
                        <label for="percussion"><input type="checkbox" id="percussion" name="categories[]" value="Percussion">Percussion</label><br>
                        <label for="phaser"><input type="checkbox" id="phaser" name="categories[]" value="Phaser">Phaser</label><br>
                        <label for="piano"><input type="checkbox" id="piano" name="categories[]" value="Piano">Piano</label><br>
                        <label for="reverb"><input type="checkbox" id="reverb" name="categories[]" value="Reverb">Reverb</label><br>
                        <label for="sampler"><input type="checkbox" id="sampler" name="categories[]" value="Sampler">Sampler</label><br>
                        <label for="sequencer"><input type="checkbox" id="sequencer" name="categories[]" value="Sequencer">Sequencer</label><br>
                        <label for="soft_synth"><input type="checkbox" id="soft_synth" name="categories[]" value="Soft Synth">Soft Synth</label><br>
                        <label for="sound_design"><input type="checkbox" id="sound_design" name="categories[]" value="Sound Design">Sound Design</label><br>
                        <label for="string"><input type="checkbox" id="string" name="categories[]" value="String">String</label><br>
                        <label for="surround"><input type="checkbox" id="surround" name="categories[]" value="Surround">Surround</label><br>
                        <label for="synth"><input type="checkbox" id="synth" name="categories[]" value="Synth">Synth</label><br>
                        <label for="vocal"><input type="checkbox" id="vocal" name="categories[]" value="Vocal">Vocal</label><br>
                        <label for="wavetable_synth"><input type="checkbox" id="wavetable_synth" name="categories[]" value="Wavetable Synth">Wavetable Synth</label><br>
                        <label for="world"><input type="checkbox" id="world" name="categories[]" value="World">World</label><br>
                        <label for="acoustic_drum"><input type="checkbox" id="acoustic_drum" name="categories[]" value="Acoustic Drum">Acoustic Drum</label><br>
                        <label for="analog_drum"><input type="checkbox" id="analog_drum" name="categories[]" value="Analog Drum">Analog Drum</label><br>
                        <label for="brass"><input type="checkbox" id="brass" name="categories[]" value="Brass">Brass</label><br>
                        <label for="cinematic"><input type="checkbox" id="cinematic" name="categories[]" value="Cinematic">Cinematic</label><br>
                        <label for="digital_drum"><input type="checkbox" id="digital_drum" name="categories[]" value="Digital Drum">Digital Drum</label><br>
                        <label for="fx"><input type="checkbox" id="fx" name="categories[]" value="FX">FX</label><br>
                        <label for="hip_hop"><input type="checkbox" id="hip_hop" name="categories[]" value="Hip Hop">Hip Hop</label><br>
                        <label for="house"><input type="checkbox" id="house" name="categories[]" value="House">House</label><br>
                        <label for="jazz"><input type="checkbox" id="jazz" name="categories[]" value="Jazz">Jazz</label><br>
                        <label for="metal"><input type="checkbox" id="metal" name="categories[]" value="Metal">Metal</label><br>
                        <label for="orchestra"><input type="checkbox" id="orchestra" name="categories[]" value="Orchestra">Orchestra</label><br>
                        <label for="pop"><input type="checkbox" id="pop" name="categories[]" value="Pop">Pop</label><br>
                        <label for="rock"><input type="checkbox" id="rock" name="categories[]" value="Rock">Rock</label><br>
                        <label for="sfx"><input type="checkbox" id="sfx" name="categories[]" value="SFX">SFX</label><br>
                        <label for="sound_effects"><input type="checkbox" id="sound_effects" name="categories[]" value="Sound Effects">Sound Effects</label><br>
                        <label for="soundscapes"><input type="checkbox" id="soundscapes" name="categories[]" value="Soundscapes">Soundscapes</label><br>
                        <label for="urban"><input type="checkbox" id="urban" name="categories[]" value="Urban">Urban</label><br>
                        <label for="vocals"><input type="checkbox" id="vocals" name="categories[]" value="Vocals">Vocals</label><br>
                        <label for="woodwind"><input type="checkbox" id="woodwind" name="categories[]" value="Woodwind">Woodwind</label><br>
                        <label for="world_percussion"><input type="checkbox" id="world_percussion" name="categories[]" value="World Percussion">World Percussion</label><br>
                        <label for="other"><input type="checkbox" id="other" name="categories[]" value="Other">Other</label><br>

                        </div>

                    </div>


                    
                    <div class = "formSection">
                        <label for="price">Price:</label><br>
                        <input type="number" id="price" name="price" step="0.01" placeholder="Leave blank if free"><br>
                    </div>
                    
                    <div class = "formSection">
                        <label for="dlPage">Download Page:</label><br>
                        <input type="url" id="dlPage" name="dlPage"><br>
                    </div>
                    
                    <div class = "formSection">
                        <label for="demo">Demo Available:</label><br>
                        <input type="checkbox" id="demo" name="demo"><br>
                    </div>
                    
                    <div class = "formSection">
                        <label for="releaseDate">Release Date:</label><br>
                        <input type="date" id="releaseDate" name="releaseDate"><br>
                    </div>

                    <div class = "formSection">    
                        <label for="longDescription">Long Description:</label><br>
                        <textarea id="longDescription" name="longDescription" rows="10" cols="80" placeholder="Required. You might want to type this out in a text editor and copy and paste it here..." required></textarea><br>
                    </div>

                    <div class = "formSection"> 
                        
                            <label for="shortDescription">Short Description:</label><br>
                            <textarea id="shortDescription" name="shortDescription" rows="10" cols="80" placeholder = "Required. Short summary for the catalog page" required></textarea><br>

                    </div>

                    <div class="formSection">
                        <div id = "imageURLsContainer">
                            <label for="pluginImages">Plugin Image URLs:</label><br>
                            <button type="button" id="addImageURL" class="addrem">+</button>
                            <button type="button" id="removeImageURL" class="addrem">-</button><br>
                            <input type="text" id="pluginImageURLs" name="pluginImages[]" placeholder="Enter image URL" required><br>
                        </div>
                    </div>

                    <div class="formSection">
                        <label for="catalogImageURL">Catalog Image URL:</label><br>
                        <input type="text" id="catalogImageURL" name="catalogImageURL" placeholder="Enter catalog image URL" required><br>
                    </div>

                    

                    <input type="submit" value="Preview">
                </form>
            <div>
        </div>
</div>
    <script>

document.addEventListener('DOMContentLoaded', function() {

    const imageURLsContainer = document.getElementById('imageURLsContainer'); // Updated ID here
    const addImageURLButton = document.getElementById('addImageURL');
    const removeImageURLButton = document.getElementById('removeImageURL');

    addImageURLButton.addEventListener('click', function() {
        const imageURLInput = document.createElement('input');
        imageURLInput.type = 'text';
        imageURLInput.name = 'pluginImages[]';
        imageURLInput.placeholder = 'Enter image URL';
        imageURLsContainer.appendChild(imageURLInput);
    });

    removeImageURLButton.addEventListener('click', function() {
        const imageURLInputs = imageURLsContainer.querySelectorAll('input[name="pluginImages[]"]');
        if (imageURLInputs.length > 1) {
            const lastImageURLInput = imageURLInputs[imageURLInputs.length - 1];
            lastImageURLInput.remove();
        }
    });

});

    </script>
</body>
</html>
