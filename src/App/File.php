<?php

namespace Varvara\Framework\App;

class File
{
    public function file(): void
    {
        echo "
<div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
            <form action='/upload' method='post' enctype='multipart/form-data'>
            <input type='file' name='csv' value='' />
            <input name='organization_id' value='' />
            <input type='submit' name='submit' value='Save' /></form>
            </div>
        ";
    }
    public function upload(): void
    {
        echo "f";
        // echo $_FILES["csv"]["name"];
        echo $_FILES['csv']['tmp_name'];
        ;
    }
}
