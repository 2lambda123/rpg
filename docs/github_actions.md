name: My Workflow
   on:
     push:
       branches:
         - main
     pull_request:
       branches:
         - main
   jobs:
     build:
       runs-on: ubuntu-latest
       steps:
         - name: Checkout code
           uses: actions/checkout@v2
          - name: Build project
            run: |
              npm install
              npm run build
           run: |
             npm install
             npm run build
   ```

3. Creating a New Workflow File:
   To create a new workflow file, follow these steps:
   - Go to the **Actions** tab in your repository.
   - Click on the **Set up a workflow yourself** link.
   - Edit the YAML file with your desired workflow configuration.

## Defining Jobs and Steps
In GitHub Actions, a job represents a set of steps that run on the same runner. Here's how you can define jobs and steps in a workflow file:

```yaml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v2
      - name: Build project
        run: |
          npm install
          npm run build
```

The `jobs` section defines the jobs in the workflow. Each job has a unique name and runs on a specific runner. The `steps` section contains the individual steps to be executed within the job.

## Using Environment Variables
Environment variables can be used to store and access data within GitHub Actions workflows. Here's how you can define and use environment variables:

```yaml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Set up environment
        run: echo "MY_VARIABLE=123" >> $GITHUB_ENV
      - name: Use environment variable
        run: echo $MY_VARIABLE
