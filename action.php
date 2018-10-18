<?php
require_once('rotation.php');

header('Content-Type: application/json');

if(isset($_POST)) {
    if(isset($_POST['wave']) && isset($_POST['value'])) {
        if($_POST['wave'] == 1 && is_numeric($_POST['value'])) {
            $value = $_POST['value'];

            if($value > 5 || $value < 1)
                exit();

            switch($value) {
                case 1:
                    questionSecondWave(1);
                    break;
                case 2:
                    questionSecondWave(2);
                    break;
                case 3:
                    questionSecondWave(3);
                    break;
                case 4:
                    questionSecondWave(4);
                    break;
                case 5:
                    questionSecondWave(5);
                    break;
                default:
                    exit();
            }
        }
    }
    if(isset($_POST['first']) && isset($_POST['second']) && isset($_POST['extra'])) {
        $wave = 3;
        $start = $_POST['second'] + 2;

        if($_POST['extra'] == 'true') {
            $wave = 3;
            $start = $_POST['second'] + 2;
        }

        $rotation = new Rotation($start, $wave);
        $rotation->arrayBuilder();
        $result = $rotation->build();

        echo json_encode(array('status' => 'success', 'data' => $result));
    }
}

function questionSecondWave($value) {
    switch($value) {
        case 1:
            $html = '<option class="select" value="">Select</option>
                     <option value="3">South East & South West</option>
                     <option value="7">South East & Center</option>
                     <option value="12">South & Center</option>';
            break;
        case 2:
            $html = '<option class="select" value="">Select</option>
                     <option value="2">South West & North West</option>
                     <option value="8">South West & South East</option>
                     <option value="13">South East & South</option>';
            break;
        case 3:
            $html = '<option class="select" value="">Select</option>
                     <option value="0">South West & Center</option>
                     <option value="5">North West & South</option>
                     <option value="9">South West & South</option>';
            break;
        case 4:
            $html = '<option class="select" value="">Select</option>
                     <option value="14">South East & South West</option>
                     <option value="extra">North West & South</option>';
            break;
        case 5:
            $html = '<option class="select" value="">Select</option>
                     <option value="1">North West & Center</option>
                     <option value="4">South East & South</option>
                     <option value="10">North West & South</option>';
            break;
    }
    echo json_encode(array('status' => 'success', 'data' => $html));
}