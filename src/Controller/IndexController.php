<?php

namespace Varvara\Framework\Controller;

class IndexController
{
    public function index(): void
    {
        echo "
            <div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
            <h1> Welcome to main page </h1>
            <div style='display:flex; justify-content:center; flex-direction:column; '>
                        <a href='/file' style = 'padding: 20px; '><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Upload File</button></a>
                        <a href = '/generate-form' style = 'padding: 20px; '><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Generate Data</button></a>
                        <a href = '/filter' style = 'padding: 20px;'><button style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Filter Data</button></a>
            </div>
            </div>
            ";
    }

    public function showGenerateForm(): void
    {
        echo "<div style='background: lightpink; color: white; padding: 20px;  height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; '>
          <form method='POST' action='/generate'>
                <input type='number' name='quantity' min='1' max='100' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>
                <button type='submit' style = 'width: 250px; height: 50px; font-size: 20px; border-radius: 12px; background: white'>Generate</button>
          </form>
          </div>";
    }

}
