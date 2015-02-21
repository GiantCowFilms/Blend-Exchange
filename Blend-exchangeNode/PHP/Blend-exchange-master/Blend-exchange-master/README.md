# Blend-exchange
This is a WIP project to add "permiment" blend hosting for blender.stackexchange until we can convice the SE lords to add the feature

###Basic idea:

Users can upload a file into a repo here on github or any free filestorage system that can be accessed publicly from the web and written to programatically
The users will be required to include a question link inorder to insure it is only used for BSE questions, since space is valuable.

In an HTTP header often the site that the link was clicked on his provided... if more references come from not BSE or not the question, it can be reviewed, automating abuse control


###TODO for the project

 - Database to store the id, name, location, password, question and a validation (once it has been investigated, it should stop throwing flags)
 - Another table to hold views and downloads, especially where they came from, as well as a **Hash** of the ip, to see if all the valid source are a single person etc.
 - **ACCESS THE GITHUB API** I've spent many hours trying... please tell me if you want to give it a shot, soon I might switch it to nodejs, which has some nice libraries
 
