Assignment 3: README FILE
Information
Alan Yuan, 999011242, c1yuanal
Brien Premachandiran, 998897252, g2premac
Bug Report
When the second player accepts the first player's request, the first player needs to reload his page in order to play the connect 4 game.
SecurImage
We downloaded the SecurImage files and put it in the root directory of the connect4 folder. In the newForm.php file created in the starter code, we added an extra form_input that takes the user's guess into the captcha. After that, we added a new input validation rule into create new account that checks if the user's input is correct.
Invite Player/ Setup
Once a user was invited, we would setup the playing board.  We setup an 2-D array containing the information of the pieces . We would use the models to collect the necessary information on the two users and then update their playing status. To ensure concurrency, we don't commit unless if there are no errors and then we call database commit. We communicate between the two players using JSON objects and modifying only the canvas.
Board Detection:
For the Board Detection, we would iterate through all the possible combinations in the array and return the user if true, else false. 
