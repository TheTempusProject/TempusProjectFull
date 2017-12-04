# The Tempus Project
#### Rapid Prototyping Framework
###### Developer(s): Joey Kimsey

The aim of this project is to provide a simple and stable platform from which to easily add functionality. The goal being the ability to quickly build and test new projects with a lightweight ecosystem to help.

**Notice: This code is in _still_ not production ready. This framework is provided as is, use at your own risk.**
I am working very hard to ensure the system is safe and reliable enough for me to endorse its widespread use, but it still needs a lot of QA and improvements.

Currently I am in the process of testing all the systems in preparation for the first production ready release. The beta is still on-going. If you would like to participate or stay up to date with the latest, you can find more information at: https://TheTempusProject.com/beta

## Installation

Preferred method for installation is using composer.
1. Clone the directory to wherever you want to install the framework.
2. Open your terminal to the directory you previously cloned the repository.
3. Install using composer:
`php composer.phar install`
4. Open your browser and navigate to install.php (it will be in the root directory of your installation)
5. When prompted, complete the form and submit.

If you have any trouble with the installation, you can check out our FAQ page on the wiki for answers to comon issues.

If you would like a full copy of the project with all of its included dependencies you can find it at https://github.com/TheTempusProject/TempusProjectFull
Please note this repository is only up to the latest _stable_ release. Please continue to use composer update to get the latest development releases.

**Do not forget to remove install.php once you have finished installation!**

#### Currently being developed:
- [ ] Code refactoring
- [ ] Adding documentation
- [ ] Unit tests
- [ ] Edits for PSR conformity

#### Future updates
- [ ] Expansion of PDO to allow different database types
- [ ] Update installer to account for updates.
- [ ] Impliment uniformity in terms of error reporting, exceptions, logging.