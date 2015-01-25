Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/trusty64"

  config.vm.network "forwarded_port", guest: 9001, host: 9001

  config.vm.provision :shell, inline: "sudo apt-get install -qq -y php5"
  config.vm.provision :shell, path: "resources/bootstrap.sh"
  config.vm.provision :shell, inline: "supervisord -c /vagrant/resources/supervisord.conf", run: "always", privileged: false
end
