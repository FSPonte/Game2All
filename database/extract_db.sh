sudo mysql -e "use game2all; select * from users;" | sed "s/\t/,/g" > database/users.csv
sudo mysql -e "use game2all; select * from \`groups\`;" | sed "s/\t/,/g" > database/groups.csv
sudo mysql -e "use game2all; select * from group_members;" | sed "s/\t/,/g" > database/group_members.csv
