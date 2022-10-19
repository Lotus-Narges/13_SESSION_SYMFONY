
## Notes:
#### __toString():
 - We put this function src/Entity of each table
 - Formatting the DATE datatype -> we do it in string function
    -> "Birth_date: " . $this->birth_date->format('d/m/Y')


#### Calculating interval differences:
 - We create a function (getAge) in Entity/Entity.php before __toString():
    -> public function getAge() {
        $now = new \DateTime();
        $interval = date_diff($this->birth_date, $now);
        return $interval -> format("%Y");
    }


 - Calling the function getAge():
    -> Age: {{employe.age}} years <br>

    {# we created the function which calculate the age in controller/Entity/entity.php 
    When we name the method we create get/has/is.age -> It goes automatically search the methods which are called get/has/is.age
    Each time symfony search to find a method -> it search them by their pre-fix #}

##### Whenever we have the Error "date couldn't converted to string -> We need to format the date


### ManagerRegistory:
 - The class which allow us to communicate with DB 
 - To be able to get the date from DB we need to use the native functions of symfony (findAll(), findBy(), ...)

### raw -> 
 - The raw filter marks the value as being "safe", which means that in an environment with automatic escaping enabled this variable will not be escaped if raw is the last filter applied to it

### striptags('<br>') ->
 - {# striptags('<br>') -> Allow us to ignore the <br> tag #}

Session_cours -> __toString(),  showing the name of the session  & cours just by their id in templates

### Display a table in twig ->
<table>
    <tr>
        <td>id</td>
        <td>username</td>
    </tr>
    {% for item in entities %}
        <tr>
            <td>{{ item.id }}</td>
            <td>{{ item.username }}</td>
        </tr>
    {% endfor %}
</table>

### MAILER_DSN=smtp://localhost:1025
-> manage the connection between our mailing system
-> Port 8025: See the mail reception
-> Poet 1025: The post which allow us to manage sending
