# line_notify
การทำ Line Notify ดึงข้อมูลเข้า line กลุ่มแบบต่างๆ
ให้สร้าง line token เพื่อส่งเข้าไปในกลุ่มโดย สมัคร line notify token ที่ https://notify-bot.line.me/th/ 
แล้วเอามาใส่ในไฟล์ linenotify_conf.php ตรงที่ Authorization: Bearer xxxx แทนคำว่า xxxx


หากอยากสร้าง บน server ไว้รันให้สร้าง crontab ด้วยครับ วิธีการคือ

#วิธีใช้งาน Crontab (Cron Jobs) บน Linux Server มีตัวอย่างใน Floder Documents

crontab คือคำสั่งที่จะทำงานตามเวลาที่กำหนด ซึ่งจะช่วยทำให้เราสะดวกขึ้นมากในการที่จะต้องสั่งงานบางอย่างซ้ำๆ กันหลายๆ ครั้ง อาจจะเป็นทุกๆ ชั่วโมง ทุกๆ วัน หรือ ทุกๆ เดือน เช่นการสั่งให้ Server ทำ Backup ทุกๆ สัปดาห์

คำสั่งและ option ของ crontab มีดังนี้

#crontab filename การนำเอาคำสั่ง crontab เข้ามาจาก ไฟล์อื่น
#crontab -e แก้ไข crontab ปัจจุบัน (ส่วนมากเราจะเพิ่มคำสั่งลงไปในนี้เลย)
#crontab -l ดูคำสั่ง crontab ทั้งหมดที่มีอยู่
#crontab -r ลบคำสั่ง crontab ที่มีทั้งหมด
#crontab -u user เป็นคำสั่งของผู้ดูแลระบบเท่านั้น เพื่อใช้ดู แก้ไข ลบ crontab ของ user แต่ละคน
เมื่อเรียกคำสั่งตามข้างบนแล้ว crontab จะเข้าสู่ระบบการ กำหนด หรือแก้ไข ซึ่งการกำหนด หรือแก้ไขนี้จะเหมือนกับการใช้งาน vi

รูปแบบของคำสั่ง crontab มีทั้งหมด 6 fields ดังนี้

1 = minute มีค่า 0 – 59 เวลาเป็นนาที จะสั่งให้คำสั่งที่กำหนดทำงานทันที่เมื่อถึงนาทีที่กำหนด
2 = hour มีค่า 0 – 23 เวลาเป็นชั่วโมง จะสั่งให้คำสั่งที่กำหนดทำงานทันที่เมื่อถึงชั่วโมงที่กำหนด
3 = day มีค่า 1 – 31 เวลาเป็นวัน จะสั่งให้คำสั่งที่กำหนดทำงานทันที่เมื่อถึงวันที่กำหนด
4 = month มีค่า 1 – 12 เวลาเป็นเดือน จะสั่งให้คำสั่งที่กำหนดทำงานทันที่เมื่อถึงเดือนที่กำหนด
5 = weekday มีค่า 0 – 6 วันขะงแต่ละสัปดาห์ มีค่าดังนี้ (อาทิตย์ = 0, จันทร์ = 1, อังคาร = 2, พุธ = 3, พฤหัส = 4, ศุกร์ = 5 ,เสาร์ = 6 )
6 = command คำสั่ง เราสามารถกำหนดคำสั่งได้มากมาย รวมทั้ง script ต่าง ๆ ตามที่เราต้องการ
โดยสามารถจำเป็นรูปแบบง่ายได้ดังนี้

* * * * * command to be executed
- - - - -
| | | | |
| | | | ----- Day of week (0 - 7) (Sunday=0 or 7)
| | | ------- Month (1 - 12)
| | --------- Day of month (1 - 31)
| ----------- Hour (0 - 23)
------------- Minute (0 - 59)

#เริ่มเพิ่ม Cron Jobs โดยใช้คำสั่ง

crontab -e

จากนั้นเพิมพ์คำสั่งดังต่อไปนี้ได้เลย

1 2 3 4 5 /path/to/script.sh

ยกตัวอย่างคำสั่ง Crontab รูปแบบต่างๆ

การสั่งให้ /path/to/command ทำงานเมื่อเวลาผ่านไป 5นาที หลังเที่ยงคืน ในทุกๆ วัน

5 0 * * * /path/to/command

การสั่งให้ /path/to/script.sh ทำงานเวลา 14.25น. ของวันที่ 1 ในทุกๆ เดือน

15 14 1 * * /path/to/script.sh

การสั่งให้ /scripts/phpscript.php ทำงานเวลา 22.00น. ในทุกๆ วัน ยกเว้นวันเสาร์ อาทิตย์

0 22 * * 1-5 /scripts/phpscript.php

ทุก 4  ซม 

*   */4  *  *  *

ทุก 8,12,16 น

0 8,12,16 * * * service tomcat restart



โดยปกติแล้วเมื่อ Cron Jobs ทำงานระบบจะส่ง E-Mail เพื่อแจ้งให้ Server Admin หรือ User ที่ตั้ง Cron Jobs ทราบว่า Cron Jobs ทำงานเรียบร้อยหรือติดปัญหาอย่างไร

หากต้องการให้ Cron Jobs ส่ง E-Mail ไปยังบัญชีที่เราตั้งค่าไว้เราสามารถเพิ่มคำสั่งได้ดังนี้

MAILTO="admin@domain.com"
0 3 * * * /root/backup.sh

หากเราไม่ต้องการให้ระบบส่ง E-Mail เราสามารถตั้งค่า Cron Jobs ได้โดยเพิ่ม /dev/null 2>&1 ตามหลัง command ที่จะสั่งให้ทำงาน ยกตัวอย่างเช่น

0 1 5 10 * /path/to/script.sh >/dev/null 2>&1

#ทำการ save แล้วสั่ง

service crond reload

ทำการ restart

service crond restart

/etc/init.d/crond restart

#ตัวอย่างCrontab แบบทำตอนเวลา 8.00 น จันทร์-ศุกร์
0 8 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/start.php
0 9 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotifyperson_alltype.php
0 9 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotifyperson_type13.php
0 9 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotifyperson_dup.php
0 9 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotifyperson_error_home.php
0 9 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotifypersondbpop.php
0 10 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotifycservice.php
0 10 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/linenotify_teda4i.php
0 10 * * 1-5 curl http://xxx.xxx.xxx.x/line_notify/end.php
