wget https://nodejs.org/dist/v20.11.0/node-v20.11.0-linux-x64.tar.xz

tar -xf node-v20.11.0-linux-x64.tar.xz

cp -p node-v20.11.0-linux-x64/bin/node /usr/local/bin/

update-alternatives --install /usr/bin/node node /usr/local/bin/node 1

rm -rf node-v20.11.0-linux-x64.tar.xz

rm -rf node-v20.11.0-linux-x64

curl -L https://www.npmjs.com/install.sh | npm_install="latest" sh

update-alternatives --install /usr/bin/npm npm /usr/local/lib/node_modules/npm/bin/npm-cli.js 1

update-alternatives --display npm
