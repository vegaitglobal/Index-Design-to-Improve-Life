# Index-Design-to-Improve-Life
INDEX: Design to Improve Life® is a Danish NPO with global reach. We Inspire, Educate and Engage in designing sustainable solutions to global challenges.
How to start:

    PHP-Redis version:
	    Requirements:
		    Apache
		    PHP
		    Redis
		    RediSearch - https://oss.redislabs.com/redisearch/Quick_Start/
		
	    Start:  
		    redis-server --loadmodule ./redisearch.so  
		
	    Crawling:  
		    To run crawer manually start-crawler.php  
		    To run scheduled crawling add cron jobs in /etc/crontab file  
		
        Front:
            Dev:
		        Install Node.js 
		        Install Npm
		        Run 'npm install' in front root folder to install dependencies, then run 'npm run dev'  
		
	    Prod: Build files are in 'dist' folder, to build again run 'npm run build'
	
