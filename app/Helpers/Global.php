<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;


if (!function_exists('moneyFormat')) {
    /**
     * moneyFormat
     *
     * @param  mixed $str
     * @return string
     */
    function moneyFormat($str)
    {
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}

if (!function_exists('phoneFormat')) {
    /**
     * moneyFormat
     *
     * @param  mixed $str
     * @return integer
     */
    function phoneFormat($str)
    {
        $result = preg_replace('/\s+/', '', str_replace(["-", "_"], "", $str));
        if (str_starts_with($result, 0)) {
            $result = "62" . substr($result, 1);
        }
        return intval($result);
    }
}

function getList($model, $title, $show = 'name', $attributeName = '', $attributeValue = 'id', $hasChild = false, $whereKey = '', $whereValue = '')
{
    $class = str_replace("'", "", "App\Models\'") . ucfirst($model);
    if ($whereKey != '') {
        $data = $class::where($whereKey, $whereValue)->get()->toArray();
    } else {
        $data = $class::get()->toArray();
    }
    $element = "<label for='$attributeName' class='form-label'>$title</label>
    <select name='$attributeName' data-parent=$hasChild id='$attributeName' class='form-control mb-3' required>
        <option value='0' disabled> - </option>" . "\n";
    foreach ($data as $item) {
        $element .= "<option value='$item[$attributeValue]'>" . ucfirst($item[$show]) . "</option>" . "\n";
    }
    $element .= "</select>";
    return $element;
}

function makeForm(mixed $table)
{
    $name = Schema::getColumnListing($table);
    $columns = array();
    $titleArray = [
        "name",
        'title',
        "judul"
    ];
    $blogArray = [
        "content",
        'deskripsi',
    ];
    $imageArray = [
        "photo",
        'image',
        'icon',
        'logo',
    ];
    $sosmedArray = [
        "facebook",
        'instagram',
        "linkedin",
        "twitter",
    ];
    $unsetArray = [
        'created_at',
        'updated_at',
        'id',
        'email_verified_at',
        'slug',
        'remember_token',
        'guard_name'
    ];
    $no = 0;
    $no_urut = 1;
    $total = count($name);
    foreach ($name as $item) {
        if (in_array($item, $titleArray) || str_contains($item, '_id')) {
            $no_urut = 0;
        } else if (in_array($item, $sosmedArray)) {
            $no_urut = $total;
        } else if (in_array($item, $blogArray)) {
            $no_urut = 2;
        } else if (in_array($item, $imageArray)) {
            $no_urut = 1;
        }
        $columns[$no] = [
            'no_urut' => $no_urut,
            'name' => $item,
            'type' => DB::getSchemaBuilder()->getColumnType($table, $item)
        ];
        if (in_array($item, $unsetArray)) {
            unset($columns[$no]);
        }
        $no++;
        $no_urut++;
    }
    $data = collect($columns)->sortBy('no_urut')->toArray();
    // dd($data);
    $html = "";
    if ($table == 'users') {
        $roles = DB::select('select name from roles');
        $html = "<div class='row g-9 mb-8' id='formRoles'>
        <div class='col-md-12 fv-row'>
            <label class='required fs-6 fw-semibold mb-2'>Assign</label>
            <select class='form-select form-select-solid' data-control='select2' data-hide-search='true'
                data-placeholder='Select a Role Member' name='role'>
                <option value=''>Select roles...</option>
                ";
        foreach ($roles as $item) {
            $html .= "<option value='$item->name'>" . ucfirst($item->name) . "</option>";
        }
        $html .= "</select></div></div>";
    }

    foreach ($data as $col) {
        if ($col['name'] == 'thumbnail' || $col['name'] == 'logo' || $col['name'] == 'image' || $col['name'] == 'photo' || $col['name'] == 'icon') {
            $html .=
                "<div class='d-flex flex-column mb-8 fv-row'>
                <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                    <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                </label>
                <input type='file' accept='image/*' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                    name='" . $col['name'] . "'>
                <img class='imageForm " . $col['name'] . " mt-3' src='' width='300' onclick='$(" . $col['name'] . ").click()' />
            </div>";
        } else if ($col['name'] == 'url' || $col['name'] == 'method') {
            $html .=
                "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <input required readonly type='text' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                        name='" . $col['name'] . "'>
                </div>";
        } else if ($table == 'blogs' && ($col['name'] == 'cuplikan')) {
            $html .=
                "";
        } else if ($table == 'blogs' && ($col['name'] == 'content')) {
            $html .=
                "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <div id='kt_docs_quill_basic' style='min-height: 200px;' class='form-control'></div>
                    <input hidden type='text' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off' name='" . $col['name'] . "'>
                </div>";
        } else if (str_ends_with($col['name'], 'user_id')) {
            if (auth()->user()->roles[0]->name == 'superadmin') {
                $html .=
                    "<div class='' hidden>
                        <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                            <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                        </label>
                        <input required type='text' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                            name='" . $col['name'] . "' value='" . auth()->user()->id . "'>
                    </div>";
            } else {
                if ($col['name'] == 'user_id') {
                    $html .=
                        "<div class='' hidden>
                            <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                                <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                            </label>
                            <input required type='text' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                                name='" . $col['name'] . "' value='" . auth()->user()->id . "'>
                        </div>";
                } else {
                    $html .=
                        "<div class='' hidden>
                            <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                                <span class=''> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                            </label>
                            <input type='text' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                                name='" . $col['name'] . "'>
                        </div>";
                }
            }
        } else if ($col['name'] == 'facebook' || $col['name'] == 'twitter' || $col['name'] == 'linkedin' || $col['name'] == 'instagram' || $col['name'] == 'link') {
            $html .=
                "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class=''> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <input type='url' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                        name='" . $col['name'] . "'>
                </div>";
        } else if ($col['name'] == 'password') {
            $html .=
                "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <input type='password' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                        name='" . $col['name'] . "'>
                </div>";
        } else if (str_contains($col['name'], '_enum')) {
            $changeName = ucfirst(str_replace("_enum", "", $col['name']));
            $label = str_replace("_", " ", implode('_', array_map('ucfirst', explode('_', $changeName))));
            $data = "getEnum" . $changeName;
            $html .=
                "<div class='row g-9 mb-8'>
            <div class='col-md-12 fv-row'>
                <label class='required fs-6 fw-semibold mb-2'>$label</label>
                <select class='form-select form-select-solid' data-control='select2' data-hide-search='true'
                    data-placeholder='Select a $label' name='" . $col['name'] . "'>
                    <option value=''>Select $label...</option>
                    ";
            foreach ($data() as $item) {
                $html .= "<option value='$item'>" . ucfirst($item) . "</option>";
            }
            $html .= "</select></div></div>";
        } else if ($col['name'] == 'font_icon') {
            $changeName = ucfirst(str_replace("_enum", "", $col['name']));
            $label = str_replace("_", " ", implode('_', array_map('ucfirst', explode('_', $changeName))));
            $data = getIcon();
            $html .=
                "<div class='row g-9 mb-8'>
            <div class='col-md-12 fv-row'>
                <label class='required fs-6 fw-semibold mb-2'>$label</label>
                <select class='form-select form-select-solid' data-control='select2' data-hide-search='true'
                    data-placeholder='Select a $label' name='" . $col['name'] . "'>
                    <option value=''>Select $label...</option>
                    ";
            foreach ($data as $item) {
                $html .= "<option value='$item'><span><i style='font-size:200px;' class='$item'></i> " . ucfirst(str_replace('fas fa-', '', $item)) . "</span></option>";
            }
            $html .= "</select></div></div>";
        } else if (str_contains($col['name'], 'y_id')) {
            $changeName = ucfirst(str_replace("y_id", "ies", $col['name']));
            $label = str_replace("_", " ", implode('_', array_map('ucfirst', explode('_', $changeName))));
            $data = "getRelation";
            $html .=
                "<div class='row g-9 mb-8'>
            <div class='col-md-12 fv-row'>
                <label class='required fs-6 fw-semibold mb-2'>$label</label>
                <select class='form-select form-select-solid' data-control='select2' data-hide-search='true'
                    data-placeholder='Select a $label' name='" . $col['name'] . "'>
                    <option value=''>Select $label...</option>
                    ";
            foreach ($data($changeName) as $item) {
                $html .= "<option value='$item->id'>" . ucfirst($item->name) . "</option>";
            }
            $html .= "</select></div></div>";
        } else if (str_contains($col['name'], '_id')) {
            $changeName = ucfirst(str_replace("_id", "s", $col['name']));
            if (substr($changeName, -2) == "ss") {
                $changeName = substr($changeName, 0, -1);
            }
            $data = "getRelation";
            $html .=
                "<div class='row g-9 mb-8'>
            <div class='col-md-12 fv-row'>
                <label class='required fs-6 fw-semibold mb-2'>$changeName</label>
                <select class='form-select form-select-solid' data-control='select2' data-hide-search='true'
                    data-placeholder='Select a $changeName' name='" . $col['name'] . "'>
                    <option value=''>Select $changeName...</option>
                    ";
            foreach ($data($changeName) as $item) {
                $html .= "<option value='$item->id'>" . ucfirst($item->name) . "</option>";
            }
            $html .= "</select></div></div>";
        } else {
            if ($col['type'] == 'string') {
                $html .=
                    "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <input required type='text' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                        name='" . $col['name'] . "'>
                </div>";
            } elseif ($col['type'] == 'integer') {
                $html .=
                    "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <input required type='number' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                        name='" . $col['name'] . "'>
                </div>";
            } else if ($col['type'] == 'text') {
                $html .=
                    "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <textarea required name='" . $col['name'] . "' id='" . $col['name'] . "' cols='30' rows='5' class='form-control'></textarea>
                </div>";
            } else if ($col['type'] == 'datetime') {
                $html .=
                    "<div class='d-flex flex-column mb-8 fv-row'>
                    <label for='" . $col['name'] . "' class='d-flex align-items-center fs-6 fw-semibold mb-2'>
                        <span class='required'> " . ucfirst(str_replace("_", " ", $col['name'])) . " </span>
                    </label>
                    <input required type='date' class='form-control' id='" . $col['name'] . "' aria-describedby='" . $col['name'] . "Help' autocomplete='off'
                        name='" . $col['name'] . "'>
                </div>";
            } else if ($col['type'] == 'boolean') {
                $html .=
                    "<div class='d-flex flex-column mb-8 fv-row'>
                    <div class='d-flex flex-stack w-lg-50'>
                        <div class='me-5'>
                            <label for='" . $col['name'] . "' class='fs-6 fw-semibold form-label'>Apakah " . str_replace("Is", "", ucfirst(str_replace("_", " ", $col['name']))) . " ?</label>
                        </div>

                        <label class='form-check form-switch form-check-custom form-check-solid'>
                            <span class='form-check-label fw-semibold text-muted me-2'>
                                Tidak
                            </span>
                            <input class='form-check-input' type='checkbox' id='" . $col['name'] . "' name='" . $col['name'] . "' value='' checked='checked'/>
                            <span class='form-check-label fw-semibold text-muted'>
                                Ya
                            </span>
                        </label>
                    </div>
                </div>";
            }
        }
    }

    // dd($columns);
    return $html;
}

function getTable(mixed $table)
{
    $columns = Schema::getColumnListing($table);
    $name = [];
    $no = 0;
    $nonValidateDefault = [
        "facebook",
        "twitter",
        "linkedin",
        "instagram",
        "remember_token",
        "email_verified_at",
        'id',
        'slug',
        'updated_at',
        'created_at',
        "guard_name"
    ];
    foreach ($columns as $item) {
        $name[$no] = [
            "title" => ucfirst(str_replace("_", " ", $item)),
            "name" => $item,
            'type' => DB::getSchemaBuilder()->getColumnType($table, $item)
        ];
        if (in_array($item, $nonValidateDefault)) {
            unset($name[$no]);
        }
        $no++;
    }
    $data = collect($name)->sortBy('name')->reverse()->toArray();
    return $data;
}

function getArrayPost($request, $table)
{
    $array = collect($request->all())->toArray();
    $method = $request->_method;
    foreach (getTable($table) as $item) {
        $tableName = $item['name'];

        if ($tableName == 'image' || $tableName == 'thumbnail' || $tableName == 'logo' || $tableName == 'photo' || $tableName == 'icon') {
            if (is_file($request->$tableName)) {
                $nm = $request->$tableName;
                $namafile = Str::slug(isset($request->title) ? $request->title : $request->name) . ".png";
                $nm->move(public_path() . '/' . $table, $namafile);
                $linkImage = url('/') . '/' . $table . '/' . $namafile;
                $array[$tableName] = $linkImage;
            } else {
                if ($method == 'POST' && $table != 'company_settings') {
                    return [
                        'message' => $item['title'] . ' Harus Diisi!',
                        'type' => 'error'
                    ];
                } else {
                    if ($request->$tableName == null || $request->$tableName == "" || $request->$tableName == "undefined" || is_null($request->$tableName)) {
                        unset($array[$tableName]);
                    }
                }
            }
        } else if ($tableName == 'end_at' || $tableName == 'start_at') {
            $array[$tableName] = Carbon::parse($request->$tableName);
        } else if ($tableName == 'phone' || $tableName == 'no_hp' || $tableName == 'hp') {
            $array[$tableName] = phoneFormat($request->$tableName);
        } else if (str_ends_with($tableName, 'user_id')) {
            if (auth()->user()->roles[0]->name == 'superadmin') {
                $array[$tableName] = auth()->user()->id;
            } else {
                if ($tableName == 'user_id') {
                    $array[$tableName] = auth()->user()->id;
                } else {
                    unset($array[$tableName]);
                }
            }
        } else if ($tableName == 'status' && $item['type'] == 'boolean') {
            $array[$tableName] = true;
        } else if ($tableName == 'cuplikan') {
            $array[$tableName] = substr(str_replace(['</p>', '<br/>', '<br>', '<br />', '<p>'], " ", strip_tags($request->content, ['<br/>', '<br>', '<br />', '<p>'])), 0, 150);
        } else if ($tableName == 'tags') {
            $array[$tableName] = implode(', ', handleTags($request->tags));
        } else if (isset($array['slug'])) {
            $array['slug'] = isset($request->title) ? Str::slug($request->title) : Str::slug($request->name);
        } else {
            $array[$tableName] = $request->$tableName;
            if ($method == 'PATCH') {
                // Validasi Null Value
                if ($array[$tableName] == null || $array[$tableName] == "" || $array[$tableName] == "undefined" || !isset($array[$tableName]) || is_null($request->$tableName)) {
                    unset($array[$tableName]);
                }
            } else {
                if ($array[$tableName] == null || $array[$tableName] == "" || $array[$tableName] == "undefined" || is_null($request->$tableName)) {
                    return [
                        'message' => $item['title'] . ' Harus Diisi!',
                        'type' => 'error'
                    ];
                }
            }
        }
    }
    return $array;
}

function afterPost($request)
{
    if ($request->_method == 'PATCH') {
        Alert::toast('Data Berhasil Diupdate !', 'info');
    } else if ($request->_method == 'POST') {
        Alert::toast('Data Berhasil Ditambahakan !', 'success');
    } else if ($request->_method == 'DELETE') {
        Alert::toast('Data Berhasil Dihapus !', 'error');
    }
}
function getListRoute()
{
    $r = Route::getRoutes();
    $result = [];
    foreach ($r as $value) {
        $api = substr($value->uri(), 0, 3);
        if ($api == "api") {
            $result[] = [
                'url' => env('APP_URL') . '/' . $value->uri(),
                'method' => $value->methods()[0]
            ];
        }
    }

    return $result;
}

function getClassActive($route)
{
    $current = Route::currentRouteName();
    if ($current == $route) {
        return 'active';
    } else if (url()->current() == $route) {
        return 'active';
    }
}

function getClassShow($route)
{
    $result = '';
    $current = request()->route()->named($route);
    if ($current) {
        $result = 'show';
    }
    return $result;
}

function getCompanySetting()
{
    $data = DB::select('select * FROM company_settings WHERE id = 1');
    return $data[0];
}

function sliderText($text)
{
    $resutl = wordwrap($text, 45, "<br />\n");
    return $resutl;
}

function getColumnNameType($table)
{
    $name = Schema::getColumnListing($table);
    $columns = array();
    $no = 0;
    $nonValidateDefault = [
        "facebook",
        "twitter",
        "linkedin",
        "instagram",
        "remember_token",
        "email_verified_at",
        'id',
        'updated_at',
        'created_at',
        "guard_name"
    ];
    foreach ($name as $item) {
        $columns[$no] = [
            'name' => $item,
            'type' => DB::getSchemaBuilder()->getColumnType($table, $item)
        ];
        if (in_array($item, $nonValidateDefault)) {
            unset($columns[$no]);
        }
        $no++;
    }
    $data = collect($columns)->sortBy('name')->reverse()->toArray();
    return $data;
}

function getArrayValidator($table, $nonValidateCustom = [])
{
    $array = getColumnNameType($table);
    if ($table == 'users') {
        $arrayValidator[0] = [
            'name' => 'role',
            'validation' => 'required',
            'message' => ucfirst(str_replace("_", " ", 'role')) . " Harus Diisi."
        ];
    } else if ($table == 'blogs') {
        $nonValidateCustom = ['cuplikan'];
    } else {
        $arrayValidator = [];
    }
    $nonValidateDefault = [
        "facebook",
        "twitter",
        "linkedin",
        "instagram",
        "remember_token",
        "user_id",
        "post_user_id",
        "approve_user_id",
        "guard_name"
    ];
    $nonValidate = array_merge($nonValidateCustom, $nonValidateDefault);

    foreach ($array as $item) {
        $name = $item['name'];
        $type = $item['type'];
        if (!in_array($name, $nonValidate)) {
            $arrayValidator[] = [
                'name' => $name,
                'validation' => 'required',
                'message' => ucfirst(str_replace("_", " ", $name)) . " Harus Diisi."
            ];
        }
    }
    return $arrayValidator;
}

function customValidation($data)
{
    $array = '';
    foreach ($data as $item) {
        $name = $item['name'];
        $message = $item['message'];

        $validation = "";
        if ($item['validation'] == 'required') {
            $validation = "notEmpty";
        }
        $array .= "
        $name: {
            validators: {
                $validation: {
                    message: '$message'
                }
            }
        },";
    }
    $result['fields'] =  "{" . substr_replace($array, "}", -1);
    $result['plugins'] = "{
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: '.fv-row',
            eleInvalidClass: '',
            eleValidClass: ''
        })
    }";
    $oke = substr(json_encode($result), 0, -1);
    $format = str_replace('"', '', $oke) . "}";
    return str_replace('\n', '', $format);
}

function getAjaxForm($table)
{
    $columns = getColumnNameType($table);
    $imageArray = [
        "photo",
        'thumbnail',
        'image'
    ];
    $script = "
    var formData = new FormData();
    const _token = $('[name=_token]').val()
    const _method = $('[name=_method]').val()
    ";
    $append = "
    formData.append('_token', _token);
    formData.append('_method', _method);
    ";

    if ($table == 'users') {
        $script .= "
        const role = $('[name=role]').val()
        ";
        $append .= "
        formData.append('role', role);
        ";
    }

    $array = [];
    foreach ($columns as $item) {
        $name = $item['name'];
        if (in_array($name, $imageArray)) {
            $script .= "const $name = $('[name=$name]')[0].files[0];
            ";
            $append .= "
            formData.append('$name', $name);
            ";
        } elseif (str_ends_with($name, 'user_id')) {
            if (auth()->user()->roles[0]->name == 'superadmin') {
                $script .= "const $name = '" . auth()->user()->id . "';
                ";
                $append .= "
                formData.append('$name', $name);
                ";
            } else {
                if ($name == 'user_id') {
                    $script .= "const $name = '" . auth()->user()->id . "';
                        ";
                    $append .= "
                        formData.append('$name', $name);
                    ";
                } else {
                    $script .= "";
                    $append .= "";
                }
            }
        } else {
            $script .= "const $name = $('[name=$name]').val()
            ";
            $append .= "
            formData.append('$name', $name);
            ";
        }
        $array[$name] = $name;
    }
    $array['_token'] = '_token';
    $array['_method'] = '_method';
    if ($table == 'users') {
        $array['role'] = 'role';
    }
    $json = json_encode($array);
    $script .= $append . "const data = " . str_replace('"', '', $json) . "
    ";
    // dd($script);
    return strval($script);
}

function getFormHelper($table)
{
    $result['getArrayValidator'] = getArrayValidator($table);
    $result['getColumnNameType'] = getColumnNameType($table);
    $result['getTable'] = getTable($table);
    $result['makeForm'] = makeForm($table);
    $result['getAjaxForm'] = getAjaxForm($table);
    $result['validator'] = customValidation(getArrayValidator($table));
    return collect($result)->toArray();
}

function getEnumAnimation()
{
    $array = ['zoomin', 'zoomout', 'parallaxhorizontal', 'parallaxvertical', 'incube'];
    return $array;
}

function getEnumBusiness_category()
{
    $array = ["School", "Company", "Organization"];
    return $array;
}

function getEnumDefault_theme()
{
    $array = ["light", "dark", "system"];
    return $array;
}

function getRelation($table)
{
    $table = lcfirst($table);
    $array = DB::table($table)->get();
    // dd($array);
    return $array;
}

function getTags()
{
    $array = [];
    if (Schema::hasTable('tags')) {
        $array = DB::table('tags')->get('name')->pluck('name')->toArray();
    }
    return json_encode(array_map('ucfirst', $array));
}

function getListTable($search = "")
{
    $table = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $db = "Tables_in_" . $dbName;
    $array = [];
    foreach ($table as $item) {
        $array[] = [
            'name' => $item->$db,
        ];
    }
    return collect($array);
}

function array_to_obj($array, &$obj)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $obj->$key = new stdClass();
            array_to_obj($value, $obj->$key);
        } else {
            $obj->$key = $value;
        }
    }
    return collect($obj);
}

function arrayToObject($array)
{
    $object = new stdClass();
    return array_to_obj($array, $object);
}


function getRelationKey($table)
{
    $data = getColumnNameType($table);
    $array = [];
    foreach ($data as $item) {
        $name = $item['name'];
        if (str_contains($name, '_id')) {
            $array[] = str_replace('_id', '', $name);
        }
    }
    return $array;
}


function handleTags($tags)
{
    $array = [];
    foreach (json_decode($tags) as $item) {
        $cek = DB::select("SELECT name FROM tags WHERE name = '$item->value'");
        if (count($cek) < 1) {
            DB::insert("INSERT INTO tags (name) VALUES ('$item->value')");
        }
        $array[] = $item->value;
    }
    return $array;
}

function getIcon()
{
    $json = File::json(public_path('assets/icon.json'));
    return  $json;
}

function getDefaultDatabase()
{
    $result = [
        "users",
        "password_reset_tokens",
        "password_resets",
        "failed_jobs",
        "personal_access_tokens"
    ];
    return $result;
}
