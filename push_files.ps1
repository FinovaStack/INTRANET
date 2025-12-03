# PowerShell script to add, commit, and push files one by one with appropriate commit messages

# Get all files recursively, excluding .git directory
$files = Get-ChildItem -Recurse -File | Where-Object { $_.FullName -notlike '*\.git\*' } | ForEach-Object { $_.FullName.Replace((Get-Location).Path + '\', '') }

# Loop through each file
foreach ($file in $files) {
    # Add the file to staging
    git add $file

    # Determine commit message based on file status (though all are new, using 'Add')
    $commitMessage = "Add $file"

    # Commit the file
    git commit -m $commitMessage

    # Push to remote (assuming 'origin' and 'main' branch)
    git push origin main
}

Write-Host "All files have been added, committed, and pushed individually."