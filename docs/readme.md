将该域名代码迁移到git上

//test deploy

upload上传文件目录迁移新目录下
git代码钩子自动部署
微信相关变量配置，变更到.env文件下


/usr/local/nginx/conf/vhost hrwq-web.conf
    server
    {
        listen       80;
        server_name m.hrwq.com;
        index index.php index.html;
        root /usr/local/nginx/html/m.hrwq.com/public;

        location / {
             client_max_body_size 50M;
            try_files $uri $uri/ /index.php?$args;
            if (!-e $request_filename){
             rewrite ^/(.*)$ /index.php?s=/$1 last;
            }
        }     
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
            }
        location ~ \.php$ {
            fastcgi_pass   unix:/tmp/php-cgi.sock;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
            }

        location /status {
            stub_status on;
            access_log   off;
            }
        location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
            {
                expires      30d;
            }
        location ~ .*\.(js|css)?$
            {
                expires      12h;
            }
        access_log  /usr/local/nginx/logs/m.hrwq.com-access.log;
        error_log  /usr/local/nginx/logs/m.hrwq.com-error.log;
    }

    server
    {
        listen       80;
        server_name mcc.hrwq.com;
        index index.php index.html;
        root /usr/local/nginx/html/mcc.hrwq.com/public;

        location / {
             client_max_body_size 50M;
            try_files $uri $uri/ /index.php?$args;
            if (!-e $request_filename){
             rewrite ^/(.*)$ /index.php?s=/$1 last;
            }
        }
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
            }
        location ~ \.php$ {
            fastcgi_pass   unix:/tmp/php-cgi.sock;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
            }

        location /status {
            stub_status on;
            access_log   off;
            }
        location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
            {
                expires      30d;
            }
        location ~ .*\.(js|css)?$
            {
                expires      12h;
            }
        access_log  /usr/local/nginx/logs/mcc.hrwq.com-access.log;
        error_log  /usr/local/nginx/logs/mcc.hrwq.com-error.log;
    }