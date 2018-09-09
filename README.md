# Index-Design-to-Improve-Life
INDEX: Design to Improve Life® is a Danish NPO with global reach. We Inspire, Educate and Engage in designing sustainable solutions to global challenges.<br/>
How to start:<br/>

PHP-Redis version:<br/>
	Requirements:<br/>
		-Apache<br/>
		-PHP<br/>
		-Redis<br/>
		-RediSearch - https://oss.redislabs.com/redisearch/Quick_Start/ <br/>
		
	Start:  
		redis-server --loadmodule ./redisearch.so  
		
	Crawling:  
		To run crawer manually start-crawler.php  
		To run scheduled crawling add cron jobs in /etc/crontab file  
		
Front:<br/>
	Dev:<br/>
		Install Node.js <br/>
		Install Npm <br/>
		Run 'npm install' in front root folder to install dependencies, then run 'npm run dev' <br/>
		
	Prod: Build files are in 'dist' folder, to build again run 'npm run build'
	
