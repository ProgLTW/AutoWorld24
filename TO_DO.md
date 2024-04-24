### 1. Start
    -Nella cartella principale
    git clone git@github.com:ProgLTW/AutoWorld24.git
    -Nella cartella AutoWorld (after changes)
    cd AutoWorld
    git add .
    git commit -m "message..."
    git push
### 2. Before changes
    git fetch origin main
    git merge origin/main
### 3. After changes
    git add .
    git commit -m "message..."
    git push

