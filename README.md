![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# Data Enhancer
> A system-of-record for manually cleaned and reconciled data in our public API

Our data hub—which powers our public API—is largely build to be frangible. In most cases, it treats upstream systems as the system-of-record, and it could be recreated just using data from those upstream systems. However, sometimes, we need to do some manual clean up or enhancement of existing data, store it somewhere on a semi-permanent basis, and feed it back into the API. The data enhacer serves that role.

This system is currently a work-in-progress. Documentation will be updated as more features get added.



## Contributing

We encourage your contributions. Please fork this repository and make your changes in a separate branch. To better understand how we organize our code, please review our [version control guidelines](https://docs.google.com/document/d/1B-27HBUc6LDYHwvxp3ILUcPTo67VFIGwo5Hiq4J9Jjw).

```bash
# Clone the repo to your computer
git clone git@github.com:your-github-account/data-service-template.git

# Enter the folder that was created by the clone
cd data-service-template

# Start a feature branch
git checkout -b feature/good-short-description

# ... make some changes, commit your code

# Push your branch to GitHub
git push origin feature/good-short-description
```

Then on github.com, create a Pull Request to merge your changes into our `develop` branch.

This project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its [terms](CODE_OF_CONDUCT.md).

We welcome bug reports and questions under GitHub's [Issues](issues). For other concerns, you can reach our engineering team at [engineering@artic.edu](mailto:engineering@artic.edu)



## Licensing

This project is licensed under the [GNU Affero General Public License Version 3](LICENSE).
