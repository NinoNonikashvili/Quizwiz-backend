
<div style="display:flex; align-items: center">
  <h1 style="position:relative; top: -6px" >QuizWiz App</h1>
</div>

---
Quizwiz is a platform where users can write quizes from various categories and levels. 
Users can also register on quizwiz and save their results and progress.

#
### Table of Contents
* [Introduction](#introduction)
* [Prerequisites](#prerequisites)
* [Tech Stack](#tech-stack)
* [Getting Started](#getting-started)
* [Migrations](#migration)
* [Development](#development)
* [Resources](#resources)

#
### Prerequisites

* <img src="https://pngimg.com/uploads/php/php_PNG43.png" width="35" style="position: relative; top: 4px" /> *PHP@8.2 and up*
* <img src="https://tse1.mm.bing.net/th?id=OIP.lIIc_svaWdGdEJuEk7TBlgHaHa&pid=Api&P=0&h=220" width="35" style="position: relative; top: 4px" /> *MYSQL@8 and up*
* <img src="https://tse2.mm.bing.net/th?id=OIP.mmXEW6CkG5NfwwM3UdzXcwHaHa&pid=Api&P=0&h=220" width="35" style="position: relative; top: 4px" /> *npm@6 and up*
* <img src="https://tse1.mm.bing.net/th?id=OIP.mFob_nJmwmMPrR4V7M9sAQHaJz&pid=Api&P=0&h=220" width="35" style="position: relative; top: 6px" /> *composer@2 and up*


#
### Tech Stack

* <img src="https://tse3.mm.bing.net/th?id=OIP.Hh_tEbIb4-MagJsV6x_RZwHaHa&pid=Api&P=0&h=220" height="18" style="position: relative; top: 4px" /> [Laravel@10.x](https://laravel.com/docs/10.x/) - back-end framework
* <img src="[readme/assets/nova.png](https://img.stackshare.io/service/9599/preview.png)"  height="17" style="position: relative; top: 4px" /> [Laravel Nova](https://nova.laravel.com/) - flexible Admin Panel as espace "Super Admin"


#
### Getting Started
1\. First of all you need to clone api-quizwiz repository from github:
```sh
git clone https://github.com/RedberryInternship/api-quizwiz-nino-nonikashvili.git
```

2\. Next step requires you to run *composer install* in order to install all the dependencies.
```sh
composer install
```



3\. Now we need to set our env file. Go to the root of your project and execute this command.
```sh
cp .env.example .env
```
And now you should provide **.env** file all the necessary environment variables:

#
**MYSQL:**
>DB_CONNECTION=mysql

>DB_HOST=127.0.0.1

>DB_PORT=3306

>DB_DATABASE=*****

>DB_USERNAME=*****

>DB_PASSWORD=*****



other variables can be set to default.





##### Now, you should be good to go!



#
### Migration
if you've completed getting started section, then migrating database if fairly simple process, just execute:
```sh
php artisan migrate 
```


#
### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```




#
### Resources

* [Figma Design](https://www.figma.com/file/QTWoxa2OYVayZ04WJ0ZZ9k/QuizWiz?type=design&node-id=403-36581&mode=design&t=yeXTQC7WywNVhRFJ-0)
* [Postman API documentation](https://documenter.getpostman.com/view/33904104/2sA3BrWpbG)
* DataBade Diagram
 <img src="/public/images/drawSQL-image.png" width="600" style="position: relative; top: 4px" /> 





